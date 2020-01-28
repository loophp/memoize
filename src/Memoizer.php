<?php

declare(strict_types=1);

namespace drupol\memoize;

use ArrayAccess;
use drupol\memoize\Contract\Memoizer as MemoizerInterface;
use Exception;

/**
 * Class Memoizer.
 */
final class Memoizer implements MemoizerInterface
{
    /**
     * The cache.
     *
     * @var array<string, mixed>|ArrayAccess<string, mixed>
     */
    private $cache;

    /**
     * The callable to memoize.
     *
     * @var callable
     */
    private $callable;

    /**
     * The ID associated to the callable to memoize.
     *
     * @var string
     */
    private $callableId;

    /**
     * Memoizer constructor.
     *
     * @param callable $callable
     * @param string $callableId
     * @param ArrayAccess<string, mixed>|null $cache
     */
    public function __construct(
        callable $callable,
        string $callableId,
        ?ArrayAccess $cache = null
    ) {
        $this->cache = $cache ?? [];
        $this->callable = $callable;
        $this->callableId = $callableId;
    }

    /**
     * @param mixed ...$arguments
     *
     * @return mixed|null
     */
    public function __invoke(...$arguments)
    {
        $cacheId = json_encode(
            [
                $this->callableId,
                $arguments,
            ]
        );

        if (false === $cacheId) {
            throw new Exception('Unable to generate a unique ID from the given closure and arguments.');
        }

        $sha1 = sha1($cacheId);

        if (isset($this->cache[$sha1])) {
            return $this->cache[$sha1];
        }

        return $this->cache[$sha1] = ($this->callable)($arguments);
    }
}
