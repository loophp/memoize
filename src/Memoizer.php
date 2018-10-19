<?php

namespace drupol\Memoize;

/**
 * Class Memoizer.
 */
class Memoizer extends Memoize
{
    /**
     * The callable.
     *
     * @var callable
     */
    private $callable;

    /**
     * The time to live.
     *
     * @var int|null
     */
    private $ttl;

    /**
     * The cache ID.
     *
     * @var string|null
     */
    private $cacheId;

    /**
     * Memoizer constructor.
     *
     * @param callable $callable
     *   The callable.
     * @param string|null $cacheId
     *   The cache ID.
     * @param int|null $ttl
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
     * @return string|null
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
