<?php

declare(strict_types=1);

namespace drupol\memoize;

use Closure;
use drupol\memoize\Contract\Memoizer as MemoizerInterface;
use Exception;
use Opis\Closure\ReflectionClosure;

final class AmphpMemoizer implements MemoizerInterface
{
    private static $cache = [];

    public static function fromClosure(Closure $closure): Closure
    {
        $cache = self::$cache;

        return static function (...$arguments) use (&$cache, $closure) {
            $cacheId = json_encode(
                [
                    (new ReflectionClosure($closure))->getCode(),
                    $arguments,
                ]
            );

            if (false === $cacheId) {
                throw new Exception('Unable to generate a unique ID from the given closure and arguments.');
            }

            return $cache[sha1($cacheId)] ??= ($closure)(...$arguments);
        };
    }
}
