<?php

namespace spec\drupol\memoize;

use drupol\memoize\Memoize;
use drupol\memoize\NullCache;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Cache\Simple\ArrayCache;
use Symfony\Component\Cache\Simple\FilesystemCache;

class MemoizeSpec extends ObjectBehavior
{
    public function let()
    {
        $this->setMemoizeCacheProvider(new ArrayCache(0, false));
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(Memoize::class);
    }

    public function it_can_get_the_cache_provider()
    {
        $this::setMemoizeCacheProvider(null);
        $this::getMemoizeCacheProvider()
            ->shouldBeAnInstanceOf('drupol\memoize\NullCache');
    }

    public function it_can_memoize_a_closure()
    {
        $closure = function ($second = 2) {
            sleep($second);
            return microtime();
        };
        $this->memoize($closure, [])->shouldBe($this->memoize($closure, []));

        $closure = function () {
            return new \stdClass();
        };
        $this->memoize($closure, [])->shouldBe($this->memoize($closure, []));

        $object = new \stdClass();
        $array = [uniqid()];
        $value = uniqid();

        $closure = function ($object, $array, $value) {
            return new \stdClass();
        };
        $this->memoize($closure, [$object, $array, $value])->shouldBe($this->memoize($closure, [$object, $array, $value]));
    }

    public function it_can_memoize_a_closure_with_a_ttl()
    {
        $closure = function ($second = 2) {
            sleep($second);
            return microtime();
        };
        $this->memoize($closure)->shouldBe($this->memoize($closure));
        $this->memoize($closure, [], 0)->shouldNotBe($this->memoize($closure, [], 0));
        $this->memoize($closure, [], 3)->shouldBe($this->memoize($closure, [], 3));
    }


    public function it_can_memoize_a_callable()
    {
        $callable = '\uniqid';

        $this->memoize($callable, [])->shouldBe($this->memoize($callable, []));
    }

    public function it_can_use_another_cache_object()
    {
        $this::setMemoizeCacheProvider();

        $closure = function ($second = 2) {
            sleep($second);
            return microtime();
        };

        $this::getMemoizeCacheProvider()
            ->shouldImplement('Psr\SimpleCache\CacheInterface');

        $this::getMemoizeCacheProvider()
            ->shouldBeAnInstanceOf('drupol\memoize\NullCache');

        $cache = new FilesystemCache();
        $this::setMemoizeCacheProvider($cache);
        $this::getMemoizeCacheProvider()->shouldBe($cache);

        $this->memoize($closure, [])->shouldBe($this->memoize($closure, []));
    }
}
