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
        $cacheItem = $cache->getItem('foo');
        $cacheItem->set('bar');
        $cache->save($cacheItem);

        $this->beConstructedWith($cache);

        $this->shouldHaveType(ArrayAccessCacheItemPool::class);

        $this['foo']
            ->shouldReturn('bar');

        $this['bar']
            ->shouldReturn(
                $cache->getItem('bar')->get()
            );

        $this->offsetSet('a', 'b');
        $this['a']
            ->shouldReturn(
                $cache->getItem('a')->get()
            );

        $this->offsetSet(1, '2');
        $this['1']
            ->shouldReturn(
                $cache->getItem('1')->get()
            );
    }
}
