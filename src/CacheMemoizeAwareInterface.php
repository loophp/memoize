<?php

declare(strict_types = 1);

namespace drupol\memoize;

use Psr\SimpleCache\CacheInterface;

/**
 * Interface CacheMemoizeInterface.
 */
interface CacheMemoizeAwareInterface extends ExecuteCallableAwareInterface
{
    /**
     * Get the cache.
     *
     * @return \Psr\SimpleCache\CacheInterface
     *   The cache object
     */
    public function getMemoizeCacheProvider(): CacheInterface;

    /**
     * Get the cache time to live.
     *
     * @return null|\DateInterval|int
     *   The cache time to live
     */
    public function getTtl();

    /**
     * Memoize a callable.
     *
     * @param callable $callable
     *   The callable
     * @param array $parameters
     *   The callable's parameters
     * @param null|\DateInterval|int $ttl
     *   The time to live of the cache
     *
     * @return null|mixed
     *   The result of the callable
     */
    public function memoize(callable $callable, array $parameters = [], $ttl = null);

    /**
     * Set the cache.
     *
     * @param \Psr\SimpleCache\CacheInterface $cache
     *   The cache object
     *
     * @return \drupol\memoize\CacheMemoizeAwareInterface
     *   The cache memoize object
     */
    public function setMemoizeCacheProvider(CacheInterface $cache): CacheMemoizeAwareInterface;

    /**
     * Set the cache time to live.
     *
     * @param null|\DateInterval|int $ttl
     *   The time to live
     *
     * @return \drupol\memoize\CacheMemoizeAwareInterface
     *   The cache memoize object
     */
    public function setTtl($ttl): CacheMemoizeAwareInterface;
}
