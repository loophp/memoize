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
     * Set the cache time to live.
     *
     * @param int|\DateInterval|null $ttl
     *   The time to live.
     *
     * @return \drupol\memoize\CacheMemoizeAwareInterface
     *   The cache memoize object.
     */
    public function setTtl($ttl): CacheMemoizeAwareInterface;

    /**
     * Get the cache time to live.
     *
     * @return int|\DateInterval|null
     *   The cache time to live.
     */
    public function getTtl();

    /**
     * Set the cache.
     *
     * @param \Psr\SimpleCache\CacheInterface $cache
     *   The cache object.
     *
     * @return \drupol\memoize\CacheMemoizeAwareInterface
     *   The cache memoize object.
     */
    public function setMemoizeCacheProvider(CacheInterface $cache): CacheMemoizeAwareInterface;

    /**
     * Get the cache.
     *
     * @return \Psr\SimpleCache\CacheInterface
     *   The cache object.
     */
    public function getMemoizeCacheProvider(): CacheInterface;

    /**
     * Memoize a callable.
     *
     * @param callable $callable
     *   The callable.
     * @param array $parameters
     *   The callable's parameters.
     * @param int|\DateInterval|null $ttl
     *   The time to live of the cache.
     *
     * @return mixed|null
     *   The result of the callable.
     */
    public function memoize(callable $callable, array $parameters = [], $ttl = null);
}
