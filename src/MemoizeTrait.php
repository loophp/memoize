<?php

namespace drupol\Memoize;

use Psr\SimpleCache\CacheInterface;
use Symfony\Component\Cache\Simple\ArrayCache;

/**
 * Trait MemoizeTrait.
 *
 * @package drupol\Memoize
 */
trait MemoizeTrait
{
    /**
     * @var CacheInterface
     */
    protected static $cache;

    /**
     * Set the cache.
     *
     * @param \Psr\SimpleCache\CacheInterface $cache
     */
    public static function setMemoizeCacheProvider(CacheInterface $cache)
    {
        self::$cache = $cache;
    }

    /**
     * Get the cache.
     *
     * @return \Psr\SimpleCache\CacheInterface
     */
    public static function getMemoizeCacheProvider()
    {
        if (!(self::$cache instanceof CacheInterface)) {
            self::setMemoizeCacheProvider(new ArrayCache(null, false));
        }

        return self::$cache;
    }

    /**
     * Clear the cache.
     */
    public static function clearMemoizeCacheProvider()
    {
        self::getMemoizeCacheProvider()->clear();
    }

    /**
     * Memoize a closure.
     *
     * @param \Closure $func
     *   The closure.
     * @param array $parameters
     *   The closure's parameters.
     * @param string $cacheId
     *   The cache ID to use to store or retrieve the cached result.
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
    public function memoize(\Closure $func, array $parameters = [], $cacheId = null, $ttl = null)
    {
        if (null === $cacheId || !is_string($cacheId)) {
            $cacheId = $this->hash(func_get_args());
        }

        if (is_null(self::getMemoizeCacheProvider()->get($cacheId))) {
            $result = call_user_func_array($func->bindTo($this, get_called_class()), $parameters);
            self::getMemoizeCacheProvider()->set($cacheId, $result, $ttl);
        }

        return self::getMemoizeCacheProvider()->get($cacheId);
    }

    /**
     * Return a sha1 hash of an array.
     *
     * @param mixed[] $arguments
     *   The array of arguments.
     *
     * @return string
     *   The sha1 hash.
     */
    protected function hash(array $arguments = [])
    {
        return sha1(
            serialize(
                array_map(
                    function ($value) {
                        if (is_array($value)) {
                            return $this->hash($value);
                        }

                        if (is_object($value)) {
                            return spl_object_hash($value);
                        }

                        return $value;
                    },
                    $arguments
                )
            )
        );
    }
}
