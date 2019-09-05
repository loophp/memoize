[![Latest Stable Version](https://img.shields.io/packagist/v/drupol/memoize.svg?style=flat-square)](https://packagist.org/packages/drupol/memoize)
 [![GitHub stars](https://img.shields.io/github/stars/drupol/memoize.svg?style=flat-square)](https://packagist.org/packages/drupol/memoize)
 [![Total Downloads](https://img.shields.io/packagist/dt/drupol/memoize.svg?style=flat-square)](https://packagist.org/packages/drupol/memoize)
 [![Build Status](https://img.shields.io/travis/drupol/memoize/master.svg?style=flat-square)](https://travis-ci.org/drupol/memoize)
 [![Scrutinizer code quality](https://img.shields.io/scrutinizer/quality/g/drupol/memoize/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/drupol/memoize/?branch=master)
 [![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/drupol/memoize/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/drupol/memoize/?branch=master)
 [![Mutation testing badge](https://badge.stryker-mutator.io/github.com/drupol/memoize/master)](https://stryker-mutator.github.io)
 [![License](https://img.shields.io/packagist/l/drupol/memoize.svg?style=flat-square)](https://packagist.org/packages/drupol/memoize)
 [![Say Thanks!](https://img.shields.io/badge/Say-thanks-brightgreen.svg?style=flat-square)](https://saythanks.io/to/drupol)
 [![Donate!](https://img.shields.io/badge/Donate-Paypal-brightgreen.svg?style=flat-square)](https://paypal.me/drupol)
 
# PHP Memoize

## Description

Memoize functions.

From wikipedia: 
> In computing, memoization is an optimization technique used primarily to speed up computer programs by storing the results of expensive function calls and returning the cached result when the same inputs occur again.

This library help you to memoize callable or closures.

It can use any type of Cache backend system, as long as it implements [the PSR-6 cache interface](https://www.php-fig.org/psr/psr-16).

If you use the [symfony/cache](https://packagist.org/packages/symfony/cache) package, you will have a bunch of cache backends available such as Redis, MemCache, Filesystem, ArrayCache,...

## Features

* Provides a Memoizer class,
* Allows you to set a Cache backend provider (PSR-6 compliant only),
* Allows you to set a "TTL" (time to live).

## Installation

With composer:

`composer require drupol/memoize`

You will need to provide a Cache object to the library in order to get it working, ex:

`composer require symfony/cache`

The tests are using `symfony/cache` but you are free to use any other library implementing [the PSR-6 cache interface](https://www.php-fig.org/psr/psr-6/).

## Usage

```php
<?php

declare(strict_types=1);

include 'vendor/autoload.php';

use drupol\memoize\Memoizer;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

$callback = function ($a = 5, $b = 10) {
    sleep(5);

    return \sprintf('Param 1: %s  Param 2: %s%s', $a, $b, \PHP_EOL);
};

$cache = new FilesystemAdapter();

$memoizer = new Memoizer($callback, 'unique ID', $cache);

echo $memoizer('A', 'B') . "\n";
echo $memoizer('C', 'D') . "\n";
echo $memoizer('A', 'B') . "\n";
echo $memoizer('C', 'D') . "\n";
```

## Code style, code quality, tests and benchmarks

The code style is following [PSR-12](https://www.php-fig.org/psr/psr-12/) plus a set of custom rules, the package [drupol/php-conventions](https://github.com/drupol/php-conventions)
is responsible for this.

Every time changes are introduced into the library, [Travis CI](https://travis-ci.org/drupol/memoize/builds) run the tests and the benchmarks.

The library has tests written with [PHPSpec](http://www.phpspec.net/).
Feel free to check them out in the `spec` directory. Run `composer phpspec` to trigger the tests.

[PHPInfection](https://github.com/infection/infection) is used to ensure that your code is properly tested, run `composer infection` to test your code.

## Contributing

See the file [CONTRIBUTING.md](.github/CONTRIBUTING.md) but feel free to contribute to this library by sending Github pull requests.