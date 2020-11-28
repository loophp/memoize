[![Latest Stable Version][latest stable version]][packagist]
 [![GitHub stars][github stars]][packagist]
 [![Total Downloads][total downloads]][packagist]
 [![GitHub Workflow Status][github workflow status]][github actions]
 [![Scrutinizer code quality][code quality]][code quality link]
 [![Type Coverage][type coverage]][sheperd type coverage]
 [![Code Coverage][code coverage]][code quality link]
 [![License][license]][packagist]
 [![Donate!][donate github]][github sponsor]
 [![Donate!][donate paypal]][paypal sponsor]

# PHP Memoize

## Description

Memoizer class for callable.

From wikipedia:
> In computing, memoization is an optimization technique used primarily to speed up computer programs by storing the results of expensive function calls and returning the cached result when the same inputs occur again.

This library help you to memoize callable or closures.

It can use any type of Cache backend system, as long as it implements [the PSR-6 cache interface](https://www.php-fig.org/psr/psr-6).

If you use the [symfony/cache](https://packagist.org/packages/symfony/cache) package, you will have a bunch of cache backends available such as Redis, MemCache, Filesystem, ArrayCache,...

## Features

* Provides a Memoizer class,
* Allows you to set a custom cache,

## Installation

With composer:

`composer require loophp/memoize`

## Usage

```php
<?php

declare(strict_types=1);

namespace App;

include 'vendor/autoload.php';

use Closure;
use loophp\memoize\Memoizer;

$fibonacci = static function (int $number) use (&$fibonacci): int {
    return (1 >= $number) ?
        $number :
        $fibonacci($number - 1) + $fibonacci($number - 2);
};

$fibonacci = Memoizer::fromClosure($fibonacci);

function bench(Closure $closure, ...$arguments): array
{
    $start = microtime(true);

    return [
        $closure(...$arguments),
        microtime(true) - $start,
    ];
}

var_dump(sprintf('[return: %s] [duration: %s]', ...bench($fibonacci, 50)));
var_dump(sprintf('[return: %s] [duration: %s]', ...bench($fibonacci, 50)));
```

## Code style, code quality, tests and benchmarks

The code style is following [PSR-12](https://www.php-fig.org/psr/psr-12/) plus a set of custom rules, the package [drupol/php-conventions](https://github.com/drupol/php-conventions)
is responsible for this.

Every time changes are introduced into the library, [Github CI](https://github.com/drupol/memoize/actions) run the tests and the benchmarks.

The library has tests written with [PHPSpec](http://www.phpspec.net/).
Feel free to check them out in the `spec` directory. Run `composer phpspec` to trigger the tests.

[PHPInfection](https://github.com/infection/infection) is used to ensure that your code is properly tested, run `composer infection` to test your code.

## Contributing

See the file [CONTRIBUTING.md](.github/CONTRIBUTING.md) but feel free to contribute to this library by sending Github pull requests.

[latest stable version]: https://img.shields.io/packagist/v/loophp/memoize.svg?style=flat-square
[packagist]: https://packagist.org/packages/loophp/memoize

[github stars]: https://img.shields.io/github/stars/loophp/memoize.svg?style=flat-square

[total downloads]: https://img.shields.io/packagist/dt/loophp/memoize.svg?style=flat-square

[github workflow status]: https://img.shields.io/github/workflow/status/loophp/memoize/Continuous%20Integration?style=flat-square
[github actions]: https://github.com/loophp/memoize/actions

[code quality]: https://img.shields.io/scrutinizer/quality/g/loophp/memoize/master.svg?style=flat-square
[code quality link]: https://scrutinizer-ci.com/g/loophp/memoize/?branch=master

[type coverage]: https://shepherd.dev/github/loophp/memoize/coverage.svg
[sheperd type coverage]: https://shepherd.dev/github/loophp/memoize

[code coverage]: https://img.shields.io/scrutinizer/coverage/g/loophp/memoize/master.svg?style=flat-square
[code quality link]: https://img.shields.io/scrutinizer/quality/g/loophp/memoize/master.svg?style=flat-square

[license]: https://img.shields.io/packagist/l/loophp/memoize.svg?style=flat-square

[donate github]: https://img.shields.io/badge/Sponsor-Github-brightgreen.svg?style=flat-square
[github sponsor]: https://github.com/sponsors/drupol

[donate paypal]: https://img.shields.io/badge/Sponsor-Paypal-brightgreen.svg?style=flat-square
[paypal sponsor]: https://www.paypal.me/drupol

[phpspec]: http://www.phpspec.net/
[grumphp]: https://github.com/phpro/grumphp
[infection]: https://github.com/infection/infection
[phpstan]: https://github.com/phpstan/phpstan
[psalm]: https://github.com/vimeo/psalm
[changelog-md]: https://github.com/loophp/memoize/blob/master/CHANGELOG.md
[git-commits]: https://github.com/loophp/memoize/commits/master
[changelog-releases]: https://github.com/loophp/memoize/releases
