<?php

namespace spec\drupol\Memoize;

use drupol\Memoize\Memoizer;
use PhpSpec\ObjectBehavior;

class MemoizerSpec extends ObjectBehavior
{
    public function let()
    {
        $closure = function() {
            return uniqid();
        };
        $this->beConstructedWith($closure);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Memoizer::class);
    }

    function it_can_memoize_a_closure() {
        $args = [new \StdClass, [uniqid()], uniqid()];

        $this->__invoke()->shouldBe($this->__invoke());
        $this->__invoke(1)->shouldBe($this->__invoke(1));
        $this->__invoke($args)->shouldBe($this->__invoke($args));
        $this->__invoke(1)->shouldNotBe($this->__invoke(2));
    }
}
