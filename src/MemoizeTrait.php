<?php

namespace drupol\Memoize;

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
     * {@inheritdoc}
     */
    public static function setMemoizeCacheProvider(CacheInterface $cache)
    {
        self::$cache = $cache;
    }

    /**
     * {@inheritdoc}
     */
    public static function getMemoizeCacheProvider()
    {
        if (!(self::$cache instanceof CacheInterface)) {
            self::setMemoizeCacheProvider(new ArrayCache(0, false));
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
    public function memoize(callable $callable, array $parameters = [], $cacheId = null, $ttl = null)
    {
        if (null === $cacheId || !is_string($cacheId)) {
            $cacheId = $this->hash(func_get_args());
        }

        if (is_null(self::getMemoizeCacheProvider()->get($cacheId))) {
            if ($callable instanceof \Closure) {
                $callable = $callable->bindTo($this, get_called_class());
            }

            $result = $callable(...$parameters);

            self::getMemoizeCacheProvider()->set($cacheId, $result, $ttl);

            return $result;
        }

        return self::getMemoizeCacheProvider()->get($cacheId);
    }

    /**
     * Return a sha1 hash of an array.
     *
     * @param mixed[] $arguments
     *   The array of arguments.
     *
     * @return string
     *   The sha1 hash.
     */
    protected function hash(array $arguments = [])
    {
        return sha1(
            serialize(
                array_map(
                    function ($value) {
                        if (is_array($value)) {
                            return $this->hash($value);
                        }

                        if (is_object($value)) {
                            return spl_object_hash($value);
                        }

                        return $value;
                    },
                    $arguments
                )
            )
        );
    }
}
