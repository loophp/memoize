<?php

declare(strict_types=1);

namespace loophp\memoize;

use ArrayObject;
use Closure;
use loophp\memoize\Contract\Memoizer as MemoizerInterface;

final class Memoizer implements MemoizerInterface
{
    public static function fromClosure(Closure $closure, ?ArrayObject $cache = null): Closure
    {
        $cache = $cache ?? new ArrayObject();

        return static fn (...$arguments) => $cache[sha1(json_encode($arguments))] ??= ($closure)(...$arguments);
    }
}
