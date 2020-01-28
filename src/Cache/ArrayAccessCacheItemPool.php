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
     * @psalm-suppress ImplementedReturnTypeMismatch
     * @psalm-suppress MixedReturnTypeCoercion
     * @psalm-suppress InvalidReturnStatement
     *
     * @return array<string, CacheItemInterface>|Traversable<string, CacheItemInterface>
     */
    public function getItems(array $keys = [])
    {
        return $this->cache->getItems($keys);
    }

    /**
     * @psalm-param string $key
     *
     * @param string $key
     *
     * @return bool
     */
    public function hasItem($key)
    {
        return $this->cache->hasItem($key);
    }

    /**
     * @psalm-param int|string|mixed $offset
     *
     * @param mixed $offset
     *
     * @return bool
     */
    public function offsetExists($offset)
    {
        return $this->cache->hasItem((string) $offset);
    }

    /**
     * @psalm-param int|string|mixed $offset
     *
     * @param mixed $offset
     *
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->cache->getItem((string) $offset)->get();
    }

    /**
     * @psalm-param int|string|mixed $offset
     *
     * @param mixed $offset
     * @param mixed $value
     *
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        $this->cache->save(($this->cache->getItem((string) $offset))->set($value));
    }

    /**
     * @psalm-param int|string|mixed $offset
     *
     * @param mixed $offset
     *
     * @return void
     */
    public function offsetUnset($offset)
    {
        $this->cache->deleteItem((string) $offset);
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
