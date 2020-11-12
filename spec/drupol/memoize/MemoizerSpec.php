<?php

declare(strict_types=1);

namespace spec\drupol\memoize;

use Closure;
use Exception;
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

        $fibonacci = static function (int $number) use (&$fibonacci): int {
            if (1 >= $number) {
                return $number;
            }

            return $fibonacci($number - 1) + $fibonacci($number - 2);
        };

        $durationInSeconds = (new BenchmarkFactory())
            ->fromClosure($fibonacci, 25)
            ->run()
            ->getDuration()
            ->asSecond();

        $this::fromClosure($fibonacci, 'fibonacci')(25);

        $this::fromClosure($fibonacci, 'fibonacci')
            ->shouldTakeLessThan($durationInSeconds)
            ->during('__invoke', [25]);
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
            ->shouldThrow(Exception::class)
            ->during('__invoke', $p);
    }
}
