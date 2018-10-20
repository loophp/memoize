<?php

namespace drupol\Memoize;

use Psr\SimpleCache\CacheInterface;

/**
 * Interface MemoizeInterface.
 */
interface MemoizeInterface
{
    /**
     * Set the cache.
     *
     * @param \Psr\SimpleCache\CacheInterface $cache
     */
    public static function setMemoizeCacheProvider(CacheInterface $cache);

    /**
     * Get the cache.
     *
     * @return \Psr\SimpleCache\CacheInterface
     */
    public static function getMemoizeCacheProvider();

    /**
     * Clear the cache.
     */
    public static function clearMemoizeCacheProvider();

    /**
     * Memoize a callable.
     *
     * @param callable $callable
     *   The callable.
     * @param array $parameters
     *   The callable's parameters.
     * @param string $cacheId
     *   The cache ID to use to store or retrieve the cached result.
     * @param null|int|\DateInterval $ttl
     *   Optional. The TTL value of this item. If no value is sent and
     *   the driver supports TTL then the library may set a default value
     *   for it or let the driver take care of that.
     *
     * @return mixed|null
     *   The result of the callable.
     */
    public function memoize(callable $callable, array $parameters = [], string $cacheId = null, $ttl = 0);
}
