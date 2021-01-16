<?php

declare(strict_types=1);

namespace spec\loophp\memoize;

use ArrayObject;
use Closure;
use Error;
use loophp\nanobench\BenchmarkFactory;
use PhpSpec\ObjectBehavior;

class MemoizerSpec extends ObjectBehavior
{
    public function it_can_memoize_a_closure(): void
    {
        $callback = static function (...$args) {
            return implode('', ...$args);
        };

        $this::fromClosure($callback)
            ->shouldBeAnInstanceOf(Closure::class);

        $sleep = static function (int $number): int {
            sleep($number);

            return $number;
        };

        $durationInSeconds = (new BenchmarkFactory())
            ->fromClosure($sleep, 5)
            ->run()
            ->getDuration()
            ->asSecond();

        $test = $this::fromClosure($sleep);

        $test(5);

        $test
            ->shouldTakeLessThan($durationInSeconds)
            ->during('__invoke', [5]);
    }

    public function it_can_memoize_a_closure_with_a_predefined_cache(): void
    {
        $cacheStorage = new ArrayObject();

        $sleep = static function (int $number): int {
            sleep($number);

            return $number;
        };

        $test = $this::fromClosure($sleep, $cacheStorage);

        $test(5);

        if (false === $cacheStorage->offsetExists(sha1(serialize([5])))) {
            throw new Error('The cache hasn\'t been updated!');
        }

        $durationInSeconds = (new BenchmarkFactory())
            ->fromClosure($sleep, 5)
            ->run()
            ->getDuration()
            ->asSecond();

        $test = $this::fromClosure($sleep, $cacheStorage);

        $test(5);

        $test
            ->shouldTakeLessThan($durationInSeconds)
            ->during('__invoke', [5]);
    }
}
