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
     * Memoizer constructor.
     *
     * @param callable $callable
     *   The callable.
     * @param int $ttl
     *   The time to live.
     */
    public function __construct(callable $callable, $ttl = null)
    {
        $this->callable = $callable;
        $this->ttl = $ttl;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke()
    {
        return $this->memoize($this->callable, func_get_args(), $this->ttl);
    }
}
