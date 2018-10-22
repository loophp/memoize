<?php

declare(strict_types = 1);

namespace drupol\memoize;

/**
 * Class Memoizer.
 */
class Memoizer extends AbstractCacheMemoize implements CallableAwareInterface
{
    /**
     * The callable property.
     *
     * @var callable
     */
    private $callable;

    /**
     * {@inheritdoc}
     */
    public function getCallable(): callable
    {
        return $this->callable;
    }

    /**
     * {@inheritdoc}
     */
    public function setCallable(callable $callable): CacheMemoizeAwareInterface
    {
        $this->callable = $callable;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke()
    {
        return $this->memoize(
            $this->getCallable(),
            \func_get_args(),
            $this->getTtl()
        );
    }
}
