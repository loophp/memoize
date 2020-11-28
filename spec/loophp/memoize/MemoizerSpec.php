<?php

declare(strict_types=1);

namespace spec\loophp\memoize;

use ArrayObject;
use Closure;
use Error;
use loophp\nanobench\BenchmarkFactory;
use PhpSpec\ObjectBehavior;
use TypeError;

class MemoizerSpec extends ObjectBehavior
{
    public function it_can_memoize_a_closure(): void
    {
        $callback = static function (...$args) {
            return implode('', ...$args);
        };

        $this::fromClosure($callback)
            ->shouldBeAnInstanceOf(Closure::class);

        $fibonacci = static function (int $number) use (&$fibonacci): int {
            return (1 >= $number) ?
                $number :
                $fibonacci($number - 1) + $fibonacci($number - 2);
        };

        $durationInSeconds = (new BenchmarkFactory())
            ->fromClosure($fibonacci, 30)
            ->run()
            ->getDuration()
            ->asSecond();

        $test = $this::fromClosure($fibonacci);

        $test(30);

        $test
            ->shouldTakeLessThan($durationInSeconds)
            ->during('__invoke', [30]);
    }

    public function it_can_memoize_a_closure_with_a_predefined_cache(): void
    {
        $cacheStorage = new ArrayObject();

        $fibonacci = static function (int $number) use (&$fibonacci): int {
            return (1 >= $number) ?
                $number :
                $fibonacci($number - 1) + $fibonacci($number - 2);
        };

        $test = $this::fromClosure($fibonacci, $cacheStorage);

        $test(10);

        if (false === $cacheStorage->offsetExists(sha1(serialize(json_encode([10]))))) {
            throw new Error('The cache hasn\'t been updated!');
        }

        $durationInSeconds = (new BenchmarkFactory())
            ->fromClosure($fibonacci, 30)
            ->run()
            ->getDuration()
            ->asSecond();

        $test = $this::fromClosure($fibonacci, $cacheStorage);

        $test(30);

        $test
            ->shouldTakeLessThan($durationInSeconds)
            ->during('__invoke', [30]);
    }

    public function it_throws_an_error_when_it_is_unable_to_serialize(): void
    {
        $callback = static function () {
            return uniqid();
        };

        $p = [
            'Valid ASCII' => 'a',
            'Valid 2 Octet Sequence' => "\xc3\xb1",
            'Invalid 2 Octet Sequence' => "\xc3\x28",
            'Invalid Sequence Identifier' => "\xa0\xa1",
            'Valid 3 Octet Sequence' => "\xe2\x82\xa1",
            'Invalid 3 Octet Sequence (in 2nd Octet)' => "\xe2\x28\xa1",
            'Invalid 3 Octet Sequence (in 3rd Octet)' => "\xe2\x82\x28",
            'Valid 4 Octet Sequence' => "\xf0\x90\x8c\xbc",
            'Invalid 4 Octet Sequence (in 2nd Octet)' => "\xf0\x28\x8c\xbc",
            'Invalid 4 Octet Sequence (in 3rd Octet)' => "\xf0\x90\x28\xbc",
            'Invalid 4 Octet Sequence (in 4th Octet)' => "\xf0\x28\x8c\x28",
            'Valid 5 Octet Sequence (but not Unicode!)' => "\xf8\xa1\xa1\xa1\xa1",
            'Valid 6 Octet Sequence (but not Unicode!)' => "\xfc\xa1\xa1\xa1\xa1\xa1",
        ];

        $this::fromClosure($callback)
            ->shouldThrow(TypeError::class)
            ->during('__invoke', $p);
    }
}
