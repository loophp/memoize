[![Build Status](https://www.travis-ci.org/drupol/memoize.svg?branch=master)](https://www.travis-ci.org/drupol/memoize)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/drupol/memoize/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/drupol/memoize/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/drupol/memoize/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/drupol/memoize/?branch=master)
[![StyleCI](https://styleci.io/repos/104074893/shield?branch=master)](https://styleci.io/repos/104074893)
[![Latest Stable Version](https://poser.pugx.org/drupol/memoize/v/stable)](https://packagist.org/packages/drupol/memoize)
[![Total Downloads](https://poser.pugx.org/drupol/memoize/downloads)](https://packagist.org/packages/drupol/memoize)
[![License](https://poser.pugx.org/drupol/memoize/license)](https://packagist.org/packages/drupol/memoize)

# Memoize

## Description

Memoize functions or methods.

> In computing, memoization or memoisation is an optimization technique used primarily to speed up computer programs by storing the results of expensive function calls and returning the cached result when the same inputs occur again.

## Features

* Allows you to set a cache provider (PSR-16 compliant only),
* Allows you to set a "TTL" (time to live).

## Installation

`composer require drupol/memoize`

## Usage

Using the trait:

```php
include 'vendor/autoload.php';

use drupol\Memoize\MemoizeTrait;

class myObject {
    use MemoizeTrait;
}

$myObject = new myObject();

$closure = function($second = 5) {
    sleep($second);
    return microtime(true);
};

echo $myObject->memoize($closure, [1]) . "\n"; // 1505827080.6258
echo $myObject->memoize($closure, [1]) . "\n"; // 1505827080.6258
echo $myObject->memoize($closure, [2]) . "\n"; // 1505827110.982
echo $myObject->memoize($closure, [2]) . "\n"; // 1505827110.982
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
 * Clear the cache.
 */
MemoizeTrait::clearMemoizeCacheProvider();
```

## Technical notes

During the tests and investigations doing this library, I noticed that you must disable the serialization on the default `ArrayCache()` cache object.

This is why it is initialized as such: `new ArrayCache(null, false);`

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
