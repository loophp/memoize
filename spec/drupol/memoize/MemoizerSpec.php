<?php

namespace spec\drupol\memoize;

use drupol\memoize\Memoizer;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Cache\Simple\ArrayCache;

class MemoizerSpec extends ObjectBehavior
{
    public function let()
    {
        $closure = function () {
            return uniqid();
        };
        $this->beConstructedWith($closure, 'id');
        $this::setMemoizeCacheProvider(new ArrayCache(0, false));
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(Memoizer::class);
    }

    public function it_can_memoize_a_closure()
    {
        $args = [new \StdClass, [uniqid()], uniqid()];

        $this->__invoke()->shouldBe($this->__invoke());
        $this->__invoke(1)->shouldBe($this->__invoke(1));
        $this->__invoke(1)->shouldBe($this->__invoke(1));
        $this->__invoke(2)->shouldBe($this->__invoke(2));
        $this->__invoke($args)->shouldBe($this->__invoke($args));

        $this()->shouldBe($this());
        $this(1)->shouldBe($this(1));
        $this(1)->shouldBe($this(1));
        $this(2)->shouldBe($this(2));
        $this($args)->shouldBe($this($args));
    }
}
