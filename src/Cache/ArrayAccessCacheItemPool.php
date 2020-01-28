<?php

declare(strict_types=1);

namespace drupol\memoize\Cache;

use drupol\memoize\Contract\ArrayAccessCacheItemPool as ArrayAccessCacheItemPoolInterface;
use Psr\Cache\CacheItemInterface;
use Psr\Cache\CacheItemPoolInterface;
use Traversable;

/**
 * Class ArrayAccessCacheItemPool.
 */
final class ArrayAccessCacheItemPool implements ArrayAccessCacheItemPoolInterface
{
    /**
     * @var \Psr\Cache\CacheItemPoolInterface
     */
    private $cache;

    /**
     * ArrayAccessCacheItemPool constructor.
     *
     * @param \Psr\Cache\CacheItemPoolInterface $cache
     *   The cache object to decorate.
     */
    public function __construct(CacheItemPoolInterface $cache)
    {
        $this->cache = $cache;
    }

    /**
     * {@inheritdoc}
     */
    public function clear()
    {
        return $this->cache->clear();
    }

    /**
     * {@inheritdoc}
     */
    public function commit()
    {
        return $this->cache->commit();
    }

    /**
     * {@inheritdoc}
     */
    public function deleteItem($key)
    {
        return $this->cache->deleteItem($key);
    }

    /**
     * {@inheritdoc}
     */
    public function deleteItems(array $keys)
    {
        return $this->cache->deleteItems($keys);
    }

    /**
     * {@inheritdoc}
     */
    public function getItem($key)
    {
        return $this->cache->getItem($key);
    }

    /**
     * @param array<string> $keys
     *
     * @return array<CacheItemInterface>|Traversable<CacheItemInterface>
     */
    public function getItems(array $keys = [])
    {
        return $this->cache->getItems($keys);
    }

    /**
     * {@inheritdoc}
     */
    public function hasItem($key)
    {
        return $this->cache->hasItem($key);
    }

    /**
     * @param mixed $offset
     *
     * @return bool
     */
    public function offsetExists($offset)
    {
        return $this->cache->hasItem($offset);
    }

    /**
     * @param mixed $offset
     *
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->cache->getItem($offset)->get();
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     *
     * @return mixed
     */
    public function offsetSet($offset, $value)
    {
        return $this->cache->save(($this->cache->getItem($offset))->set($value));
    }

    /**
     * @param mixed $offset
     *
     * @return void
     */
    public function offsetUnset($offset)
    {
        $this->cache->deleteItem($offset);
    }

    /**
     * {@inheritdoc}
     */
    public function save(CacheItemInterface $item)
    {
        return $this->cache->save($item);
    }

    /**
     * {@inheritdoc}
     */
    public function saveDeferred(CacheItemInterface $item)
    {
        return $this->cache->saveDeferred($item);
    }
}
