<?php

declare(strict_types=1);

namespace drupol\memoize\Contract;

/**
 * Interface Invokeable.
 */
interface Invokeable
{
    /**
     * @return mixed
     */
    public function __invoke();
}
