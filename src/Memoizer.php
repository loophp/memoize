<?php

declare(strict_types=1);

namespace drupol\memoize;

use Closure;
use drupol\memoize\Contract\Memoizer as MemoizerInterface;
use Exception;
use Opis\Closure\ReflectionClosure;

final class Memoizer implements MemoizerInterface
{
    /**
     * @var array<string, mixed>
     */
    private static array $cache = [];

    public static function fromClosure(Closure $closure, ?string $id = null): Closure
    {
        $cache = &self::$cache;

        return static function (...$arguments) use (&$cache, $closure, $id) {
            $id ??= json_encode(
                [
                    (new ReflectionClosure($closure))->getCode(),
                    $arguments,
                ]
            );

            if (false === $id) {
                throw new Exception('Unable to generate a unique ID from the given closure and arguments.');
            }

            return $cache[sha1($id)] ??= ($closure)(...$arguments);
        };
    }
}
