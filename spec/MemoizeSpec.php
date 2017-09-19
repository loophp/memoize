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
        $closure = function($second = 2) {sleep($second); return microtime();};
        $this->memoize($closure)->shouldBe($this->memoize($closure));
    }

    public function it_can_use_another_cache_object()
    {
        $closure = function($second = 2) {sleep($second); return microtime();};

        $cache = new FilesystemCache();
        $this::setMemoizeCacheProvider($cache);
        $this::getMemoizeCacheProvider()->shouldBe($cache);

        $this->memoize($closure)->shouldBe($this->memoize($closure));
    }

    public function it_can_clear_cache()
    {
        $closure = function($second = 2) {sleep($second); return microtime();};
        $result = $this->memoize($closure);
        $this::clearMemoizeCacheProvider();

        $this->memoize($closure)->shouldNotBe($result);
    }

}
