<?php

declare(strict_types=1);

namespace loophp\memoize\Contract;

use ArrayObject;
use Closure;

interface Memoizer
{
    /**
     * @psalm-param Closure(mixed...):mixed $closure
     * @psalm-param null|ArrayObject<mixed, mixed> $cache
     */
    public static function fromClosure(Closure $closure, ?ArrayObject $cache = null): Closure;
}
