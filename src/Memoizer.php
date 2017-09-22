<?php

namespace drupol\Memoize;

/**
 * Class Memoizer.
 *
 * @package drupol\Memoize
 */
class Memoizer extends Memoize
{
    /**
     * The callable.
     *
     * @var null|\ReflectionFunction
     */
    private $callable = null;

    /**
     * The time to live.
     *
     * @var int
     */
    private $ttl = null;

    /**
     * The cache ID.
     *
     * @var string
     */
    private $cacheId = null;

    /**
     * Memoizer constructor.
     *
     * @param callable $callable
     *   The callable.
     * @param string $cacheId
     *   The cache ID.
     * @param int $ttl
     *   The time to live.
     */
    public function __construct(callable $callable, $cacheId = null, $ttl = null)
    {
        $this->callable = $callable;
        $this->cacheId = $cacheId;
        $this->ttl = $ttl;
    }

    /**
     * Set the cache ID.
     *
     * @param string|null $cacheId
     *   The cache ID.
     *
     * @return $this
     */
    public function setCacheId($cacheId = null)
    {
        $this->cacheId = $cacheId;

        return $this;
    }

    /**
     * Get the cache ID.
     *
     * @return string
     *   The cache ID.
     */
    private function getCacheId()
    {
        return $this->cacheId;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke()
    {
        return $this->memoize($this->callable, func_get_args(), $this->getCacheId(), $this->ttl);
    }
}
