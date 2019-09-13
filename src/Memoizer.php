<?php

declare(strict_types=1);

namespace drupol\memoize;

use drupol\memoize\Contract\Memoizer as MemoizerInterface;
use Psr\Cache\CacheItemPoolInterface;

/**
 * Class Memoizer.
 */
final class Memoizer implements MemoizerInterface
{
    /**
     * The cache object.
     *
     * @var CacheItemPoolInterface
     */
    private $cache;

    /**
     * The callable to memoize.
     *
     * @var callable
     */
    private $callable;

    /**
     * @var string
     */
    private $callableId;

    /**
     * The cache time to live.
     *
     * @var null|\DateInterval|int
     */
    private $ttl;

    /**
     * Memoizer constructor.
     *
     * @param callable $callable
     * @param string $callableId
     * @param \Psr\Cache\CacheItemPoolInterface $cache
     * @param null $ttl
     */
    public function __construct(callable $callable, string $callableId, CacheItemPoolInterface $cache, $ttl = null)
    {
        $this->cache = $cache;
        $this->ttl = $ttl;
        $this->callable = $callable;
        $this->callableId = $callableId;
    }

    public function __invoke()
    {
        return $this->memoize(\func_get_args(), $this->getTtl());
    }

    /**
     * {@inheritdoc}
     */
    public function getTtl()
    {
        return $this->ttl;
    }

    /**
     * {@inheritdoc}
     */
    public function setTtl($ttl): MemoizerInterface
    {
        $this->ttl = $ttl;

        return $this;
    }

    /**
     * Execute a callable.
     *
     * @param array $parameters
     *   The callable's parameters
     *
     * @return null|mixed
     *   The result of the callable
     */
    private function execute(array $parameters = null)
    {
        $closure = \Closure::fromCallable($this->callable)
            ->bindTo($this, static::class);

        return null === $parameters ?
            $closure() :
            $closure(...$parameters);
    }

    /**
     * @param array $parameters
     * @param null|\DateInterval|int $ttl
     *
     * @throws \InvalidArgumentException
     * @throws \Exception
     *
     * @return null|mixed
     */
    private function memoize(array $parameters = null, $ttl = null)
    {
        $cacheId = \json_encode(
            [
                $this->callableId,
                $parameters,
                $ttl,
            ]
        );

        if (false === $cacheId) {
            throw new \Exception('Unable to generate a unique ID with your closure and arguments.');
        }

        $cache = $this->cache->getItem(\sha1($cacheId));

        if ($cache->isHit()) {
            return $cache->get();
        }

        $result = $this->execute($parameters);

        $this->cache->save(
            $cache
                ->expiresAfter($ttl)
                ->set($result)
        );

        return $result;
    }
}
