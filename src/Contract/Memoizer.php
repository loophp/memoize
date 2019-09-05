<?php

declare(strict_types=1);

namespace drupol\memoize\Contract;

/**
 * Interface Memoizer.
 */
interface Memoizer extends Invokeable
{
    /**
     * Get the cache time to live.
     *
     * @return null|\DateInterval|int
     *   The cache time to live
     */
    public function getTtl();

    /**
     * Set the cache time to live.
     *
     * @param null|\DateInterval|int $ttl
     *   The time to live
     *
     * @return \drupol\memoize\Contract\Memoizer
     *   The memoizer.
     */
    public function setTtl($ttl): Memoizer;
}
