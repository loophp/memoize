<?php

declare(strict_types = 1);

namespace drupol\memoize;

use drupol\valuewrapper\ValueWrapper;
use Psr\SimpleCache\CacheInterface;

/**
 * Class AbstractCachedMemoize.
 */
abstract class AbstractCacheMemoize extends AbstractExecuteCallableAware implements CacheMemoizeAwareInterface
{
    /**
     * The cache object.
     *
     * @var CacheInterface
     */
    private $cache;

    /**
     * The cache time to live.
     *
     * @var null|\DateInterval|int
     */
    private $ttl;

    /**
     * AbstractCacheMemoize constructor.
     *
     * @param \Psr\SimpleCache\CacheInterface $cache
     *   The cache object
     * @param null|\DateInterval|int $ttl
     *   The cache time to live
     */
    public function __construct(CacheInterface $cache, $ttl = null)
    {
        $this->cache = $cache;
        $this->ttl = $ttl;
    }

    /**
     * {@inheritdoc}
     */
    public function getMemoizeCacheProvider(): CacheInterface
    {
        return $this->cache;
    }

    /**
     * {@inheritdoc}
     */
    public function getTtl()
    {
        return $this->ttl;
    }

    /**
     * {@inheritdoc}
     */
    public function memoize(
        callable $callable,
        array $parameters = [],
        $ttl = null
    ) {
        $ttl = $ttl ?? $this->getTtl();

        $cacheId = ValueWrapper::create([
            ValueWrapper::create($callable)->hash(),
            ValueWrapper::create($parameters)->hash(),
            ValueWrapper::create($ttl)->hash(),
        ])->hash();

        if ($this->getMemoizeCacheProvider()->has($cacheId)) {
            return $this->getMemoizeCacheProvider()->get($cacheId);
        }

        $result = $this->execute($callable, $parameters);

        $this->getMemoizeCacheProvider()->set($cacheId, $result, $ttl);

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function setMemoizeCacheProvider(CacheInterface $cache): CacheMemoizeAwareInterface
    {
        $this->cache = $cache;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setTtl($ttl): CacheMemoizeAwareInterface
    {
        $this->ttl = $ttl;

        return $this;
    }
}
