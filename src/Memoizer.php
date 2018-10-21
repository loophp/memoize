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
     * Memoizer constructor.
     *
     * @param callable $callable
     *   The callable.
     * @param int|null $ttl
     *   The time to live.
     */
    public function __construct(callable $callable, $ttl = null)
    {
        $this->callable = $callable;
        $this->ttl = $ttl;
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
            $this->getTtl()
        );
    }
}
