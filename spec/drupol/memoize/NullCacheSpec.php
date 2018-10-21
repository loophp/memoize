<?php

namespace spec\drupol\memoize;

use drupol\memoize\NullCache;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class NullCacheSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(NullCache::class);
    }

    public function it_can_get()
    {
        $this->get('test')->shouldBeNull();
    }

    public function it_can_set()
    {
        $this->set('key', 'value')->shouldReturn(true);
    }

    public function it_can_delete()
    {
        $this->delete('key')->shouldReturn(true);
    }

    public function it_can_clear()
    {
        $this->clear()->shouldReturn(true);
    }

    public function it_can_has()
    {
        $this->has('key')->shouldReturn(false);
    }

    public function it_can_get_multiple()
    {
        $this
            ->getMultiple(['a', 'b', 'c'], 'default')
            ->shouldReturn([
                'a' => 'default',
                'b' => 'default',
                'c' => 'default',
            ]);
    }

    public function it_can_set_multiple()
    {
        $this->setMultiple([])->shouldReturn(true);
    }

    public function it_can_delete_multiple()
    {
        $this->deleteMultiple([])->shouldReturn(true);
    }
}
