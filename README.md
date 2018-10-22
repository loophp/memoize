[![Build Status](https://www.travis-ci.org/drupol/memoize.svg?branch=master)](https://www.travis-ci.org/drupol/memoize)
 [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/drupol/memoize/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/drupol/memoize/?branch=master)
 [![Mutation testing badge](https://badge.stryker-mutator.io/github.com/drupol/memoize/master)](https://stryker-mutator.github.io)
 [![Code Coverage](https://scrutinizer-ci.com/g/drupol/memoize/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/drupol/memoize/?branch=master)
 [![StyleCI](https://styleci.io/repos/104074893/shield?branch=master)](https://styleci.io/repos/104074893)
 [![Latest Stable Version](https://poser.pugx.org/drupol/memoize/v/stable)](https://packagist.org/packages/drupol/memoize)
 [![Total Downloads](https://poser.pugx.org/drupol/memoize/downloads)](https://packagist.org/packages/drupol/memoize)
 [![License](https://poser.pugx.org/drupol/memoize/license)](https://packagist.org/packages/drupol/memoize)

# Memoize

## Description

Memoize functions or methods.

From wikipedia: 
> In computing, memoization is an optimization technique used primarily to speed up computer programs by storing the results of expensive function calls and returning the cached result when the same inputs occur again.

This library help you to memoize callable or closures.

It can use any type of Cache backend system, as long as it implements [the standard PSR-16 CacheInterface interface](https://www.php-fig.org/psr/psr-16).

If you use the [symfony/cache](https://packagist.org/packages/symfony/cache) package, you will have a bunch of cache backends available such as Redis, MemCache, Filesystem, ArrayCache,...

## Features

* Provides a Trait,
* Provides a Memoizer,
* Allows you to set a Cache backend provider (PSR-16 compliant only),
* Allows you to set a "TTL" (time to live).

## Installation

With composer:

`composer require drupol/memoize`

You will need to provide a Cache object to the library in order to get it working, ex:

`composer require symfony/cache`

The tests are using `symfony/cache` but you are free to use any other library implementing the standard PSR-16 CacheInterface.

## Usage

Using the **trait**:

```php
include 'vendor/autoload.php';

use drupol\memoize\MemoizeTrait;
use Symfony\Component\Cache\Simple\ArrayCache;

class myObject {
    use MemoizeTrait;
}

$myObject = new myObject();
$cache = new ArrayCache();

$myObject->setMemoizeCacheProvider($cache);

$closure = function($second = 5) {
    sleep($second);
    return uniqid();
};

echo $myObject->memoize($closure, [1]) . "\n"; // 59c41136b38e9
echo $myObject->memoize($closure, [1]) . "\n"; // 59c41136b38e9
echo $myObject->memoize($closure, [2]) . "\n"; // 59c41138c4765
echo $myObject->memoize($closure, [2]) . "\n"; // 59c41138c4765
```

Using the **Memoizer class**:

```php
include 'vendor/autoload.php';

use drupol\memoize\Memoizer;
use Symfony\Component\Cache\Simple\FilesystemCache;

$closure = function($second = 5) {
    return uniqid();
};

$cache = new FilesystemCache();

$memoizer = (new Memoizer($cache))
    ->setCallable($closure);


echo $memoizer(1) . "\n"; // 59c4123661459
echo $memoizer(1) . "\n"; // 59c4123661459
echo $memoizer(2) . "\n"; // 59c4123862a4e
echo $memoizer(2) . "\n"; // 59c4123862a4e
```

## API

Find the complete API documentation at [https://not-a-number.io/memoize](https://not-a-number.io/memoize).

## Contributing

Feel free to contribute to this library by sending Github pull requests. I'm quite reactive :-)

## Sponsors

* [ARhS Development](https://www.arhs-group.com)
* [European Commission - DIGIT](https://github.com/ec-europa)
