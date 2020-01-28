<?php

declare(strict_types=1);

namespace drupol\memoize\Contract;

use ArrayAccess;
use Psr\Cache\CacheItemPoolInterface;

/**
 * Class ArrayAccessCacheItemPoolInterface.
 *
 * @template-extends ArrayAccess<string|int, mixed>
 */
interface ArrayAccessCacheItemPool extends ArrayAccess, CacheItemPoolInterface
{
}
