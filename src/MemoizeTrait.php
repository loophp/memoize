<?php

namespace drupol\Memoize;

use drupol\valuewrapper\ValueWrapper;
use Psr\SimpleCache\CacheInterface;
use Symfony\Component\Cache\Simple\ArrayCache;

/**
 * Trait MemoizeTrait.
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
            self::setMemoizeCacheProvider(new ArrayCache(0, false));
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
     *
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function memoize(callable $callable, array $parameters = [], string $cacheId = null, $ttl = null)
    {
        if (null === $cacheId) {
            $cacheId = (ValueWrapper::create([
                (ValueWrapper::create($callable))->hash(),
                (ValueWrapper::create($parameters))->hash(),
                (ValueWrapper::create($cacheId))->hash(),
                (ValueWrapper::create($ttl))->hash(),
            ]))->hash();
        }

        if (!is_null(self::getMemoizeCacheProvider()->get($cacheId))) {
            return self::getMemoizeCacheProvider()->get($cacheId);
        }

        if ($callable instanceof \Closure) {
            $callable = $callable->bindTo($this, get_called_class());
        }

        $result = $callable(...$parameters);

        self::getMemoizeCacheProvider()->set($cacheId, $result, $ttl);

        return $result;
    }
}
