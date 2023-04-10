<?php

declare(strict_types=1);

namespace Ghostwriter\Option\Traits;

use Generator;
use Ghostwriter\Option\Contract\NoneInterface;
use Ghostwriter\Option\Contract\OptionInterface;
use Ghostwriter\Option\Contract\SomeInterface;
use Ghostwriter\Option\Exception\NullPointerException;
use Ghostwriter\Option\Exception\OptionException;
use Ghostwriter\Option\None;
use Ghostwriter\Option\Option;
use Throwable;

/**
 * @template TValue
 */
trait OptionTrait
{
    private static ?NoneInterface $none = null;

    /**
     * @param null|TValue $value
     */
    private function __construct(
        private readonly mixed $value = null
    ) {
        // Singleton
    }

    final public function and(OptionInterface $option): OptionInterface
    {
        if ($this instanceof NoneInterface) {
            return $this;
        }

        return $option;
    }

    final public function andThen(callable $function): OptionInterface
    {
        if ($this instanceof NoneInterface) {
            return $this;
        }

        /** @var null|OptionInterface $result */
        $result = $function($this->value);
        if ($result instanceof OptionInterface) {
            return $result;
        }

        throw new OptionException('Callables passed to andThen() must return an instance of OptionInterface.');
    }

    final public function contains(mixed $value): bool
    {
        if ($this instanceof NoneInterface) {
            return false;
        }

        return $this->value === $value;
    }

    final public function expect(Throwable $throwable): mixed
    {
        if ($this instanceof NoneInterface) {
            throw $throwable;
        }

        return $this->value;
    }

    final public function filter(callable $function): OptionInterface
    {
        return $this->map(
            /** @param TValue $value */
            fn (mixed $value): OptionInterface => true === $function($value) ? $this : None::create()
        );
    }

    final public function flatten(): OptionInterface
    {
        return $this->map(fn (mixed $value) => $value instanceof SomeInterface ? $value : $this);
    }

    final public function getIterator(): Generator
    {
        if ($this instanceof SomeInterface) {
            /**
             * @var TValue $value
             */
            $value = $this->value;

            if (is_iterable($value)) {
                yield from $value;
            } else {
                yield $value;
            }
        }
    }

    final public function isNone(): bool
    {
        return $this instanceof NoneInterface;
    }

    final public function isSome(): bool
    {
        return $this instanceof SomeInterface;
    }

    final public function map(callable $function): OptionInterface
    {
        if ($this instanceof NoneInterface) {
            return $this;
        }

        return Option::create($function($this->value));
    }

    final public function mapOr(callable $function, mixed $fallback): mixed
    {
        if ($this instanceof NoneInterface) {
            return $fallback;
        }

        return $function($this->value);
    }

    final public function mapOrElse(callable $function, callable $fallback): mixed
    {
        if ($this instanceof NoneInterface) {
            return $fallback();
        }

        return $function($this->value);
    }

    final public function or(OptionInterface $option): OptionInterface
    {
        if ($this instanceof SomeInterface) {
            return $this;
        }

        return $option;
    }

    final public function orElse(callable $function): OptionInterface
    {
        if ($this instanceof SomeInterface) {
            return $this;
        }

        return Option::create($function());
    }

    final public function unwrap(): mixed
    {
        if ($this instanceof NoneInterface) {
            throw new NullPointerException();
        }

        return $this->value;
    }

    final public function unwrapOr(mixed $fallback): mixed
    {
        if ($this instanceof SomeInterface) {
            return $this->value;
        }

        return $fallback;
    }

    final public function unwrapOrElse(callable $function): mixed
    {
        if ($this instanceof SomeInterface) {
            return $this->value;
        }

        return $function();
    }
}
