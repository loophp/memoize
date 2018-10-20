<?php

namespace drupol\memoize;

use Psr\SimpleCache\CacheInterface;

/**
 * Class NullCache.
 */
class NullCache implements CacheInterface
{
    /**
     * {@inheritdoc}
     */
    public function get($key, $default = null)
    {
        return $default;
    }

    /**
     * {@inheritdoc}
     */
    public function set($key, $value, $ttl = null)
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function delete($key)
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function clear()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function getMultiple($keys, $default = null)
    {
        $return = [];

        foreach ($keys as $key) {
            $return[$key] = $default;
        }

        return $return;
    }

    /**
     * {@inheritdoc}
     */
    public function setMultiple($values, $ttl = null)
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteMultiple($keys)
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function has($key)
    {
        return false;
    }
}
