<?php

namespace spec\drupol\Memoize;

use drupol\Memoize\Memoize;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Cache\Simple\FilesystemCache;

class MemoizeSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(Memoize::class);
    }

    public function it_can_memoize_a_closure()
    {
        $closure = function ($second = 2) {
            sleep($second);
            return microtime();
        };
        $this->memoize($closure, [], 'id')->shouldBe($this->memoize($closure, [], 'id'));

        $closure = function () {
            return new \stdClass();
        };
        $this->memoize($closure, [], 'id')->shouldBe($this->memoize($closure, [], 'id'));

        $object = new \stdClass();
        $array = [uniqid()];
        $value = uniqid();

        $closure = function ($object, $array, $value) {
            return new \stdClass();
        };
        $this->memoize($closure, [$object, $array, $value], 'id')->shouldBe($this->memoize($closure, [$object, $array, $value], 'id'));
    }

    public function it_can_memoize_a_callable()
    {
        $callable = '\uniqid';

        $this->memoize($callable, [], 'id')->shouldBe($this->memoize($callable, [], 'id'));
    }

    public function it_can_use_another_cache_object()
    {
        $closure = function ($second = 2) {
            sleep($second);
            return microtime();
        };

        $cache = new FilesystemCache();
        $this::setMemoizeCacheProvider($cache);
        $this::getMemoizeCacheProvider()->shouldBe($cache);

        $this->memoize($closure, [], 'id')->shouldBe($this->memoize($closure, [], 'id'));
    }

    public function it_can_clear_cache()
    {
        $closure = function ($second = 2) {
            sleep($second);
            return microtime();
        };
        $result = $this->memoize($closure, [], 'id');
        $this::clearMemoizeCacheProvider();

        $this->memoize($closure, [], 'id')->shouldNotBe($result);
    }
}
