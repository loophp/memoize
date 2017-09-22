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
        $this->beConstructedWith($closure, 'id');
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(Memoizer::class);
    }

    public function it_can_memoize_a_closure()
    {
        $args = [new \StdClass, [uniqid()], uniqid()];

        $this->setCacheId('id')->__invoke()->shouldBe($this->setCacheId('id')->__invoke());
        $this->setCacheId('id')->__invoke(1)->shouldBe($this->setCacheId('id')->__invoke(1));
        $this->setCacheId('id')->__invoke(1)->shouldBe($this->setCacheId('id')->__invoke(1));
        $this->setCacheId('id')->__invoke(2)->shouldBe($this->setCacheId('id')->__invoke(2));
        $this->setCacheId('id1')->__invoke(1)->shouldNotBe($this->setCacheId('id2')->__invoke(1));
        $this->setCacheId('id1')->__invoke(2)->shouldNotBe($this->setCacheId('id2')->__invoke(3));
        $this->setCacheId('id')->__invoke($args)->shouldBe($this->setCacheId('id')->__invoke($args));

        $this->setCacheId()->shouldReturn($this);

        $this()->shouldBe($this());
        $this(1)->shouldBe($this(1));
        $this(1)->shouldBe($this(1));
        $this(2)->shouldBe($this(2));
        $this(1)->shouldNotBe($this(1));
        $this(2)->shouldNotBe($this(3));
        $this($args)->shouldBe($this($args));
    }
}
