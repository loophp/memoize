<?php

declare(strict_types=1);

namespace spec\drupol\memoize;

use drupol\memoize\Memoizer;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

class MemoizerSpec extends ObjectBehavior
{
    public function it_can_memoize_a_callable(): void
    {
        $cache = new FilesystemAdapter();
        $callback = function () {
            return \uniqid();
        };

        $this->beConstructedWith($callback, \uniqid(), $cache);

        $this()->shouldBe($this());
        $this('1')->shouldBe($this('1'));
        $this('2')->shouldBe($this('2'));
        $this('1')->shouldNotBe($this('2'));

        $this->setTtl(0);

        $this()->shouldNotBe($this());
        $this('1')->shouldNotBe($this('1'));
        $this('2')->shouldNotBe($this('2'));
        $this('1')->shouldNotBe($this('2'));

        $this->setTtl(60);

        $this()->shouldBe($this());
        $this('1')->shouldBe($this('1'));
        $this('2')->shouldBe($this('2'));
        $this('1')->shouldNotBe($this('2'));
    }

    public function it_can_memoize_a_closure(): void
    {
        $cache = new FilesystemAdapter();
        $callback = function () {
            return \uniqid();
        };

        $this->beConstructedWith($callback, \uniqid(), $cache);

        $closure = function () {
            return \uniqid();
        };

        $args = [new \StdClass(), [\uniqid()], \uniqid()];

        $this()->shouldBe($this());
        $this(1)->shouldBe($this(1));
        $this(1)->shouldBe($this(1));
        $this(2)->shouldBe($this(2));
        $this($args)->shouldBe($this($args));
        $this($args)->shouldNotBe($this());

        $this->setTtl(0);

        $this()->shouldNotBe($this());
        $this(1)->shouldNotBe($this(1));
        $this(1)->shouldNotBe($this(1));
        $this(2)->shouldNotBe($this(2));
        $this($args)->shouldNotBe($this($args));
        $this($args)->shouldNotBe($this());

        $this->setTtl(60);

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
            ->shouldThrow(\Exception::class)
            ->during('__invoke', $p);
    }

    public function it_can_memoize_with_a_different_ttl(): void
    {
        $cache = new FilesystemAdapter();
        $callback = function () {
            return \uniqid();
        };

        $this->beConstructedWith($callback, \uniqid(), $cache);

        // Disable caching.
        $this->setTtl(0);

        $this
            ->__invoke('a', 'b')
            ->shouldNotEqual(
                $this->__invoke('a', 'b')
            );

        // Disable caching.
        $this->setTtl(10);

        $this
            ->__invoke('c', 'd')
            ->shouldEqual(
                $this->__invoke('c', 'd')
            );
    }

    public function it_get_and_set_the_ttl(): void
    {
        $cache = new FilesystemAdapter();
        $callback = function () {
            return \uniqid();
        };

        $this->beConstructedWith($callback, \uniqid(), $cache);

        $this
            ->getTtl()
            ->shouldBeNull();

        $this
            ->setTtl(10);

        $this
            ->getTtl()
            ->shouldReturn(10);
    }

    public function it_is_initializable(): void
    {
        $cache = new FilesystemAdapter();
        $callback = function () {
            return \uniqid();
        };

        $this->beConstructedWith($callback, \uniqid(), $cache);

        $this->shouldHaveType(Memoizer::class);
    }
}
