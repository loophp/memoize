[![Latest Stable Version](https://img.shields.io/packagist/v/drupol/memoize.svg?style=flat-square)](https://packagist.org/packages/drupol/memoize)
 [![GitHub stars](https://img.shields.io/github/stars/drupol/memoize.svg?style=flat-square)](https://packagist.org/packages/drupol/memoize)
 [![Total Downloads](https://img.shields.io/packagist/dt/drupol/memoize.svg?style=flat-square)](https://packagist.org/packages/drupol/memoize)
 [![GitHub Workflow Status](https://img.shields.io/github/workflow/status/drupol/memoize/Continuous%20Integration?style=flat-square)](https://github.com/drupol/memoize/actions)
 [![Scrutinizer code quality](https://img.shields.io/scrutinizer/quality/g/drupol/memoize/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/drupol/memoize/?branch=master)
 [![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/drupol/memoize/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/drupol/memoize/?branch=master)
 [![Type Coverage](https://shepherd.dev/github/drupol/memoize/coverage.svg)](https://shepherd.dev/github/drupol/memoize)
 [![License](https://img.shields.io/packagist/l/drupol/memoize.svg?style=flat-square)](https://packagist.org/packages/drupol/memoize)
 [![Donate!](https://img.shields.io/badge/Donate-Paypal-brightgreen.svg?style=flat-square)](https://paypal.me/drupol)
 
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

`composer require drupol/memoize`

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

$memoizer = new Memoizer($callback, 'unique ID');

echo $memoizer('A', 'B') . "\n";
echo $memoizer('C', 'D') . "\n";
echo $memoizer('A', 'B') . "\n";
echo $memoizer('C', 'D') . "\n";
```

By default, no cache object is provided and it uses simple PHP arrays.
However, if you want to use a custom cache, the `ArrayAccessCacheItemPool` class is made for you.

Do `composer require symfony/cache` or any other PSR-6 compliant library, then:

```php
<?php

declare(strict_types=1);

include 'vendor/autoload.php';

use drupol\memoize\Cache\ArrayAccessCacheItemPool;
use drupol\memoize\Memoizer;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

// Decorate the FilesystemAdapter() cache.
$cache = new ArrayAccessCacheItemPool(new FilesystemAdapter());

$callback = function ($a = 5, $b = 10) {
    sleep(5);

    return \sprintf('Param 1: %s  Param 2: %s%s', $a, $b, \PHP_EOL);
};

$memoizer = new Memoizer($callback, 'unique ID', $cache);

echo $memoizer('A', 'B') . "\n";
echo $memoizer('C', 'D') . "\n";
echo $memoizer('A', 'B') . "\n";
echo $memoizer('C', 'D') . "\n";
```

The `ArrayAccessCacheItemPool` class is a decorator for [PSR-6 CacheItemPoolInterface](https://www.php-fig.org/psr/psr-6) objects.
It implements at the same time the core [`ArrayAccess`](https://php.net/manual/en/class.arrayaccess.php) interface and `CacheItemPoolInterface` interface.
Once decorated, your PSR-6 cache will behave just like a simple PHP array.

## Code style, code quality, tests and benchmarks

The code style is following [PSR-12](https://www.php-fig.org/psr/psr-12/) plus a set of custom rules, the package [drupol/php-conventions](https://github.com/drupol/php-conventions)
is responsible for this.

Every time changes are introduced into the library, [Github CI](https://github.com/drupol/memoize/actions) run the tests and the benchmarks.

The library has tests written with [PHPSpec](http://www.phpspec.net/).
Feel free to check them out in the `spec` directory. Run `composer phpspec` to trigger the tests.

[PHPInfection](https://github.com/infection/infection) is used to ensure that your code is properly tested, run `composer infection` to test your code.

## Contributing

See the file [CONTRIBUTING.md](.github/CONTRIBUTING.md) but feel free to contribute to this library by sending Github pull requests.