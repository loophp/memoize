<?php

declare(strict_types = 1);

namespace drupol\memoize;

/**
 * Interface CallableAwareInterface.
 */
interface CallableAwareInterface
{
    /**
     * Get the callable.
     *
     * @return callable
     *   The callable
     */
    public function getCallable(): callable;

    /**
     * Set the callable.
     *
     * @param callable $callable
     *   The callable
     */
    public function setCallable(callable $callable);
}
