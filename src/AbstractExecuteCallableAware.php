<?php

declare(strict_types = 1);

namespace drupol\memoize;

/**
 * Class AbstractExecuteCallable.
 */
abstract class AbstractExecuteCallableAware implements ExecuteCallableAwareInterface
{
    /**
     * {@inheritdoc}
     */
    public function execute(callable $callable, array $parameters = [])
    {
        if ($callable instanceof \Closure) {
            $callable = $callable->bindTo($this, static::class);
        }

        return $callable(...$parameters);
    }
}
