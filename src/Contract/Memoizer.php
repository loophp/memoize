<?php

declare(strict_types=1);

namespace loophp\memoize\Contract;

use Closure;

interface Memoizer
{
    public static function fromClosure(Closure $closure): Closure;
}
