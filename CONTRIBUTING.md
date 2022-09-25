# Contributing

Contributions are **welcome** and will be fully **credited**.

We accept contributions via Pull Requests on [Github](https://github.com/jampire/appraiser).

## Pull Requests

- **[PSR-12](https://www.php-fig.org/psr/psr-12/) Coding Standard** - The easiest way to apply the conventions is to use 
[PHP Codesniffer](https://github.com/squizlabs/PHP_CodeSniffer) (included in the application).

- **Add [PHPUnit](https://phpunit.de/) tests!** - Your patch won't be accepted if it doesn't have tests. Your code 
coverage should be _100%_. Use [PHPUnit](https://phpunit.de/) (included in the application) to write your tests.

- **Static Code Analyze** - use [PHPStan](https://phpstan.org/) (included in the application) to check your code.
A violation will cause the build to fail, so please make sure there are no violations. We can't accept a patch 
if the build fails.

- **Document any change in behaviour** - Make sure the README and any other relevant documentation are kept up-to-date.

- **Consider our release cycle** - We try to follow SemVer. Randomly breaking public APIs is not an option.

- **Create topic branches** - Don't ask us to pull from your master branch.

- **One pull request per feature** - If you want to do more than one thing, send multiple pull requests.

- **Send coherent history** - Make sure each individual commit in your pull request is meaningful. 
If you had to make multiple intermediate commits while developing, please squash them before submitting.

- **Ensure tests pass!** - Please run the tests (see below) before submitting your pull request, 
and make sure they pass. We won't accept a patch until all tests pass.

## Running PHP Codesniffer

- to check style
``` bash
$ composer style
```
- to fix style
``` bash
$ composer fix-style
```

## Running PHPStan

``` bash
$ composer analyze
```

## Running Tests

``` bash
$ composer tests
```

## Running all scripts at once

``` bash
$ composer all
```

**Happy coding**!
