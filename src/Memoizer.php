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
    private $closure = null;

    /**
     * The time to live.
     *
     * @var int
     */
    private $ttl = null;

    /**
     * Memoizer constructor.
     *
     * @param callable $closure
     *   The callable.
     * @param int $ttl
     *   The time to live.
     */
    public function __construct($closure, $ttl = null)
    {
        $this->closure = new \ReflectionFunction($closure);
        $this->ttl = $ttl;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke()
    {
        return $this->memoize($this->closure->getClosure(), func_get_args(), $this->ttl);
    }
}
