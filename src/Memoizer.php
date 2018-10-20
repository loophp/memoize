<?php

namespace drupol\memoize;

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
    public function __construct(callable $callable, string $cacheId = null, $ttl = null)
    {
        $this->callable = $callable;
        $this->cacheId = $cacheId;
        $this->ttl = $ttl;
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
     * Get the TTL.
     *
     * @return int|null
     *   The TTL.
     */
    private function getTtl()
    {
        return $this->ttl;
    }

    /**
     * Get the callable.
     *
     * @return callable
     *   The callable.
     */
    private function getCallable()
    {
        return $this->callable;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke()
    {
        return $this->memoize(
            $this->getCallable(),
            func_get_args(),
            $this->getCacheId(),
            $this->getTtl()
        );
    }
}
