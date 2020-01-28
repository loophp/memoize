<?php

declare(strict_types=1);

namespace spec\drupol\memoize;

use drupol\memoize\Cache\ArrayAccessCacheItemPool;
use drupol\memoize\Memoizer;
use Exception;
use PhpSpec\ObjectBehavior;
use StdClass;
use Symfony\Component\Cache\Adapter\ArrayAdapter;

class MemoizerSpec extends ObjectBehavior
{
    public function it_can_memoize_a_callable(): void
    {
        $callback = static function (...$args) {
            return implode('', ...$args);
        };

        $this->beConstructedWith($callback, uniqid());

        $this()->shouldBe($this());
        $this('1')->shouldBe($this('1'));
        $this('2')->shouldBe($this('2'));
        $this('1')->shouldNotBe($this('2'));
    }

    public function it_can_memoize_a_closure(): void
    {
        $callback = static function () {
            return uniqid();
        };

        $this->beConstructedWith($callback, uniqid());

        $args = [new StdClass(), [uniqid()], uniqid()];

        $this()->shouldBe($this());
        $this(1)->shouldBe($this(1));
        $this(1)->shouldBe($this(1));
        $this(2)->shouldBe($this(2));
        $this($args)->shouldBe($this($args));
        $this($args)->shouldNotBe($this());

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

        $this
            ->shouldThrow(Exception::class)
            ->during('__invoke', $p);
    }

    public function it_is_initializable(): void
    {
        $callback = static function () {
            return uniqid();
        };

        $this->beConstructedWith($callback, uniqid());

        $this->shouldHaveType(Memoizer::class);
    }

    public function it_works_with_a_custom_cache(): void
    {
        $cache = new ArrayAccessCacheItemPool(new ArrayAdapter());

        $key = sha1(json_encode([]));

        $cacheItem = $cache->getItem($key);
        $cacheItem->set('bar');
        $cache->save($cacheItem);

        $callback = static function (...$arguments) {
            return implode('', ...$arguments);
        };

        $this->beConstructedWith($callback, uniqid(), $cache);

        $this->shouldHaveType(Memoizer::class);

        $this('echo')->shouldReturn('echo');
    }
}
