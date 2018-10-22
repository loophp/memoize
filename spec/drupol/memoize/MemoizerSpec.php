<?php

namespace spec\drupol\memoize;

use drupol\memoize\Memoizer;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Cache\Simple\ArrayCache;
use Symfony\Component\Cache\Simple\FilesystemCache;

class MemoizerSpec extends ObjectBehavior
{
    public function let()
    {
        $cache = new FilesystemCache();

        $this->beConstructedWith($cache);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(Memoizer::class);
    }

    public function it_can_get_set_a_callable()
    {
        $this
            ->setCallable('\uniqid');

        $this
            ->getCallable()
            ->shouldReturn('\uniqid');

        $closure = function () {
            return \uniqid();
        };

        $this
            ->setCallable($closure);

        $this
            ->getCallable()
            ->shouldReturn($closure);
    }

    public function it_can_get_and_set_the_cache()
    {
        $this
            ->getMemoizeCacheProvider()
            ->shouldBeAnInstanceOf('Psr\SimpleCache\CacheInterface');

        $cache = new ArrayCache();

        $this
            ->setMemoizeCacheProvider($cache);

        $this->getMemoizeCacheProvider()
            ->shouldBeAnInstanceOf('Psr\SimpleCache\CacheInterface');

        $this->getMemoizeCacheProvider()
            ->shouldEqual($cache);
    }

    public function it_get_and_set_the_ttl()
    {
        $this
            ->getTtl()
            ->shouldBeNull();

        $this
            ->setTtl(10);

        $this
            ->getTtl()
            ->shouldReturn(10);
    }

    public function it_can_memoize_a_closure()
    {
        $closure = function () {
            return \uniqid();
        };

        $this->setCallable($closure);

        $args = [new \StdClass, [\uniqid()], \uniqid()];

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
    }

    public function it_can_memoize_a_callable()
    {
        $callable = '\uniqid';

        $this->setCallable($callable);

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

    public function it_can_memoize_with_a_different_ttl()
    {
        $callable = '\uniqid';

        // Disable caching.
        $this->setTtl(0);

        $this
            ->memoize($callable, [], 10)
            ->shouldEqual(
                $this->memoize($callable, [], 10)
            );
    }
}
