<?php

namespace drupol\memoize;

use drupol\valuewrapper\ValueWrapper;
use Psr\SimpleCache\CacheInterface;

/**
 * Trait MemoizeTrait.
 */
trait MemoizeTrait
{
    /**
     * @var CacheInterface|null
     */
    protected static $cache;

    /**
     * {@inheritdoc}
     */
    public static function setMemoizeCacheProvider(CacheInterface $cache = null)
    {
        self::$cache = $cache;
    }

    /**
     * {@inheritdoc}
     */
    public static function getMemoizeCacheProvider()
    {
        if (!(self::$cache instanceof CacheInterface)) {
            self::setMemoizeCacheProvider(new NullCache());
        }

        return self::$cache;
    }

    /**
     * {@inheritdoc}
     */
    public static function clearMemoizeCacheProvider()
    {
        self::getMemoizeCacheProvider()->clear();
    }

    /**
     * {@inheritdoc}
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

        if (null !== ($result = self::getMemoizeCacheProvider()->get($cacheId))) {
            return $result;
        }

        if ($callable instanceof \Closure) {
            $callable = $callable->bindTo($this, get_called_class());
        }

        $result = $callable(...$parameters);

        self::getMemoizeCacheProvider()->set($cacheId, $result, $ttl);

        return $result;
    }
}
