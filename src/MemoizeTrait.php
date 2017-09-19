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
            self::setMemoizeCacheProvider(new ArrayCache());
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
    public function memoize(\Closure $func, array $parameters = [], $ttl = null)
    {
        $cacheid = spl_object_hash($func).sha1(json_encode($parameters));

        if (($cache = self::getMemoizeCacheProvider())) {
            if ($data = $cache->get($cacheid)) {
                return $data;
            }
        }

        $result = call_user_func_array($func, $parameters);

        if (($cache = self::getMemoizeCacheProvider())) {
            $cache->set($cacheid, $result, $ttl);
        }

        return $result;
    }
}
