<?php

namespace spec\drupol\Memoize;

use drupol\Memoize\Memoizer;
use PhpSpec\ObjectBehavior;

class MemoizerSpec extends ObjectBehavior
{
    public function let()
    {
        $closure = function () {
            return uniqid();
        };
        $this->beConstructedWith($closure);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(Memoizer::class);
    }

    public function it_can_memoize_a_closure()
    {
        $args = [new \StdClass, [uniqid()], uniqid()];

        $this()->shouldBe($this());
        $this(1)->shouldBe($this(1));
        $this($args)->shouldBe($this($args));
        $this(1)->shouldNotBe($this(2));
    }
}
