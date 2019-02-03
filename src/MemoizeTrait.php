<?php

declare(strict_types = 1);

namespace drupol\memoize;

use Psr\SimpleCache\CacheInterface;

/**
 * Trait MemoizeTrait.
 */
trait MemoizeTrait
{
    /**
     * The cache interface.
     *
     * @var CacheInterface
     */
    private $memoizeCache;

    /**
     * Get the cache object.
     *
     * @return \Psr\SimpleCache\CacheInterface
     *   The cache object
     */
    public function getMemoizeCacheProvider(): CacheInterface
    {
        return $this->memoizeCache;
    }

    /**
     * Memoize a callable or a closure.
     *
     * @param callable $callable
     *   The callable or the closure
     * @param array $parameters
     *   The parameters of the callable
     * @param null|\DateInterval|int $ttl
     *   The time to live of the value
     *
     * @return null|mixed
     */
    public function memoize(callable $callable, array $parameters = [], $ttl = null)
    {
        return (new Memoizer($this->getMemoizeCacheProvider()))
            ->memoize($callable, $parameters, $ttl);
    }

    /**
     * Set the cache object.
     *
     * @param \Psr\SimpleCache\CacheInterface $cache
     *   The cache object
     */
    public function setMemoizeCacheProvider(CacheInterface $cache): void
    {
        $this->memoizeCache = $cache;
    }
}
