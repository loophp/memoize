<?php

declare(strict_types = 1);

namespace drupol\memoize;

/**
 * Interface ExecuteCallableAwareInterface.
 */
interface ExecuteCallableAwareInterface
{
    /**
     * Execute a callable.
     *
     * @param callable $callable
     *   The callable
     * @param array $parameters
     *   The callable's parameters
     *
     * @return null|mixed
     *   The result of the callable
     */
    public function execute(callable $callable, array $parameters = []);
}
