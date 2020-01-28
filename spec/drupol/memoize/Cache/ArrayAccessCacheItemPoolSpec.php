<?php

declare(strict_types=1);

namespace spec\drupol\memoize\Cache;

use drupol\memoize\Cache\ArrayAccessCacheItemPool;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

class ArrayAccessCacheItemPoolSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $cache = new FilesystemAdapter();

        $this->beConstructedWith($cache);

        $this->shouldHaveType(ArrayAccessCacheItemPool::class);
    }
}
