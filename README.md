<p align="center">
    <a href="https://github.com/TatsiaA/search-issue/actions/workflows/build.yml" target="_blank" title="build">
        <img src="https://github.com/TatsiaA/search-issue/actions/workflows/build.yml/badge.svg?branch=main" alt="build">
    </a>
</p>

# Appraiser application

This application calculates the popularity of a given word in given provider ([GitHub][1], [Twitter][2], etc.)

## Table of Contents
1. [Setup](#setup)
2. [Supported Providers](#supported-providers)
   - [GitHub Provider](#github-provider)
3. [Custom providers implementation](#custom-providers-implementation)
4. [CI/CD](#cicd)
5. [Contributing](#contributing)
6. [Credits](#credits)
7. [License](#license)

## Setup
Clone `Appraiser` and install it by running following commands:
```bash
git clone git@github.com:TatsiaA/search-issue.git
cd search-issue
composer install
```
Then start docker services by running following command
```bash
docker-compose up -d --build
```
Then setup database by running following commands
```bash
docker-compose run --rm php-service php bin/console doctrine:database:create
docker-compose run --rm php-service php bin/console doctrine:migrations:migrate
```
Open up your browser and type in address bar:
```
http://localhost:8080/score?term=php&provider=github
```

## Supported Providers
GitHub provider is currently supported.

### GitHub Provider
System searches GitHub issues for a given word using the number of results for `{word} rock` s as positive results
and `{word} sucks` as negative results. Result is the popularity rating of a given word from 0-10 as a quotient of
positive and total results.

To get `php` score send following request:
```bash
curl \
  -H "Accept: application/json" \ 
  http://localhost?term=php&provider=github
```
The response will be:

```json
{
   "term": "php",
   "score": 3.42
}
```

## Custom providers implementation
To implement your own provider first add its name to [`src/Enum/Provider.php`][3] (you will use this name as
`provider` value in HTTP query string):
```php
case PROVIDER_NAME = 'provider_name';
```

Then implement your provider service. This service should extend [`src/Service/Providers/ProviderService.php`][4]
and implement following methods:
- `getProviderName()`
- `calculateNewScore()`

The name of your provider should match its name in `src/Enum/Provider.php` plus `ProviderService`.
For example, for `Twitter`:
```php
// src/Enum/Provider.php

case TWITTER = 'twitter';
```
```phpr
// src/Service/Providers/TwitterProviderService.php

class TwitterProviderService extends ProviderService
{
    ...
}
```

And finally, insert new record in provider table:
```sql
INSERT INTO provider(name, base_url, created_at, updated_at) VALUES ("twitter", "twitter_url", NOW(), NOW())
```

You can check [`src/Service/Providers/GithubProviderService.php`][5] as example.

## CI/CD
Following CI/CD jobs are urrently implemented:
- [Code Style][11] ([`PSR-12`][9])
- [Code Analyse][12] (with [`PHPStan`][10])
- [Tests][13]

You can see [`build file`][14] for mode details.

## Contributing
Thank you for considering contributing to Appraiser! You can read the contribution guide [here][6].

## Credits
- [TatsiaA][7]

## License
Appraiser is published under the [proprietary license][8].

[1]: https://github.com/
[2]: https://twitter.com/
[3]: https://github.com/TatsiaA/search-issue/blob/main/src/Enum/Provider.php
[4]: https://github.com/TatsiaA/search-issue/blob/main/src/Service/Providers/ProviderService.php
[5]: https://github.com/TatsiaA/search-issue/blob/main/src/Service/Providers/GithubProviderService.php
[6]: CONTRIBUTING.md
[7]: https://github.com/TatsiaA
[8]: LICENSE
[9]: https://www.php-fig.org/psr/psr-12/
[10]: https://phpstan.org/
[11]: https://github.com/TatsiaA/search-issue/blob/main/.github/workflows/_code_style.yml
[12]: https://github.com/TatsiaA/search-issue/blob/main/.github/workflows/_code_analyse.yml
[13]: https://github.com/TatsiaA/search-issue/blob/main/.github/workflows/_tests.yml
[14]: https://github.com/TatsiaA/search-issue/blob/main/.github/workflows/build.yml
