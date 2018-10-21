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

> In computing, memoization is an optimization technique used primarily to speed up computer programs by storing the results of expensive function calls and returning the cached result when the same inputs occur again.

## Features

* Provides a Trait, a Memoize object and a Memoizer helper,
* Allows you to set a cache provider (PSR-16 compliant only),
* Allows you to set a "TTL" (time to live).

## Installation

`composer require drupol/memoize`

You will need to provide a Cache object to the library in order to get it working.

The tests are using `symfony/cache` but you are free to use any other library implementing the cache interface (Psr\SimpleCache\CacheInterface) PSR-16.

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
$cache = new ArrayCache(0, false);

$myObject::setMemoizeCacheProvider($cache);

$closure = function($second = 5) {
    sleep($second);
    return uniqid();
};

echo $myObject->memoize($closure, [1]) . "\n"; // 59c41136b38e9
echo $myObject->memoize($closure, [1]) . "\n"; // 59c41136b38e9
echo $myObject->memoize($closure, [2]) . "\n"; // 59c41138c4765
echo $myObject->memoize($closure, [2]) . "\n"; // 59c41138c4765
```

Using the **Memoize class**:

```php
include 'vendor/autoload.php';

use Symfony\Component\Cache\Simple\ArrayCache;

class myObject extends \drupol\memoize\Memoize {
}

$myObject = new myObject();
$cache = new ArrayCache(0, false);

$myObject::setMemoizeCacheProvider($cache);

$closure = function($second = 5) {
    sleep($second);
    return uniqid();
};

echo $myObject->memoize($closure, [1]) . "\n"; // 59c411a2c6566
echo $myObject->memoize($closure, [1]) . "\n"; // 59c411a2c6566
echo $myObject->memoize($closure, [2]) . "\n"; // 59c411a4c8bb1
echo $myObject->memoize($closure, [2]) . "\n"; // 59c411a4c8bb1
```

Using the **Memoizer class**:

```php
include 'vendor/autoload.php';

use Symfony\Component\Cache\Simple\ArrayCache;

$closure = function($second = 5) {
    sleep($second);
    return uniqid();
};

$memoizer = new \drupol\memoize\Memoizer($closure);
$cache = new ArrayCache(0, false);

$memoizer::setMemoizeCacheProvider($cache);

echo $memoizer(1) . "\n"; // 59c4123661459
echo $memoizer(1) . "\n"; // 59c4123661459
echo $memoizer(2) . "\n"; // 59c4123862a4e
echo $memoizer(2) . "\n"; // 59c4123862a4e
```

## API

```php
/**
 * Set the cache.
 *
 * @param \Psr\SimpleCache\CacheInterface $cache
 */
MemoizeTrait::setMemoizeCacheProvider(CacheInterface $cache);
```

```php
/**
 * Get the cache.
 *
 * @return \Psr\SimpleCache\CacheInterface
 */
MemoizeTrait::getMemoizeCacheProvider();
```

```php
/**
 * Memoize a closure.
 *
 * @param \Closure $func
 *   The closure.
 * @param array $parameters
 *   The closure's parameters.
 * @param null|int|DateInterval $ttl
 *   Optional. The TTL value of this item. If no value is sent and
 *   the driver supports TTL then the library may set a default value
 *   for it or let the driver take care of that.
 *
 * @return mixed|null
 *   The return of the closure.
 *
 * @throws \Psr\SimpleCache\InvalidArgumentException
 */
MemoizeTrait::memoize(\Closure $func, array $parameters = [], $ttl = null);
```

## Technical notes

During the tests and investigations doing this library, I noticed that you must disable the serialization on the default `ArrayCache()` cache object.

This is why it is initialized as such: `new ArrayCache(0, false);`

If you use a cache object, make sure that you can disable the serialization or you won't be able to memoize methods that returns objects.

For example, the `FilesystemCache()` cache is unable to disable serialization and if you try to memoize functions like this example, it won't work, it's better to use the `ArrayCache()`.

```php
$function = function() {
  return new stdClass;
};
```

## Contributing

Feel free to contribute to this library by sending Github pull requests. I'm quite reactive :-)

## Sponsors

* [ARhS Development](https://www.arhs-group.com)
* [European Commission - DIGIT](https://github.com/ec-europa)
