<p align="center">
    <a href="https://github.com/TatsiaA/search-issue/actions/workflows/build.yml" target="_blank" title="build"><img src="https://github.com/TatsiaA/search-issue/actions/workflows/build.yml/badge.svg?branch=master" alt="build"></a>
    <a href="https://github.com/TatsiaA/search-issue/blob/stat/LICENSE" target="_blank" title="license"><img src="https://img.shields.io/github/license/TatsiaA/search-issue?style=flat-square" alt="license"></a>
</p>

# Appraiser application

This application calculates the popularity of a given word in given provider ([GitHub][1], [Twitter][2], etc.)

## Table of Contents
1. [Setup](#setup)
2. [Supported Providers](#supported-providers)
    - [GitHub Provider](#github-provider)
3. [Custom providers implementation](#custom-providers-implementation)

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

## Contributing
Thank you for considering contributing to Appraiser! You can read the contribution guide [here][5].

## Credits
- [TatsiaA][7]

## License
Appraiser is published under the [proprietary license][8].

[1]: https://github.com/
[2]: https://twitter.com/
[3]: https://github.com/TatsiaA/search-issue/blob/main/src/Enum/Provider.php
[4]: https://github.com/TatsiaA/search-issue/blob/main/src/Service/Providers/ProviderService.php
[4]: https://github.com/TatsiaA/search-issue/blob/main/src/Service/Providers/GithubProviderService.php
[5]: CONTRIBUTING.md
[7]: https://github.com/TatsiaA
[8]: LICENSE
