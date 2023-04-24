<?php

declare(strict_types=1);

namespace Ghostwriter\Option;

use Generator;
use Ghostwriter\Option\Contract\NoneInterface;
use Ghostwriter\Option\Contract\OptionInterface;
use Ghostwriter\Option\Contract\SomeInterface;
use Ghostwriter\Option\Exception\NullPointerException;
use Ghostwriter\Option\Exception\OptionException;
use Throwable;

/**
 * @template TOption
 *
 * @implements OptionInterface<TOption>
 *
 * @see OptionTest
 * @see SomeTest
 * @see NoneTest
 */
abstract class AbstractOption implements OptionInterface
{
    /**
     * @param TOption $value
     */
    protected function __construct(
        private readonly mixed $value
    ) {
        // Singleton
    }

    public function and(OptionInterface $option): OptionInterface
    {
        if ($this instanceof NoneInterface) {
            return $this;
        }

        return $option;
    }

    public function andThen(callable $function): OptionInterface
    {
        if ($this instanceof NoneInterface) {
            return $this;
        }

        /** @var null|OptionInterface<TOption> $result */
        $result = $function($this->value);

        if ($result instanceof OptionInterface) {
            return $result;
        }

        throw new OptionException('Callables passed to andThen() must return an instance of OptionInterface.');
    }

    public function contains(mixed $value): bool
    {
        if ($this instanceof NoneInterface) {
            return false;
        }

        return $this->value === $value;
    }

    public function expect(Throwable $throwable): mixed
    {
        if ($this instanceof NoneInterface) {
            throw $throwable;
        }

        return $this->value;
    }

    public function filter(callable $function): OptionInterface
    {
        return $this->map(
            /**
             * @param TOption $value
             *
             * @return OptionInterface<TOption>
             */
            fn (mixed $value): OptionInterface => match (true) {
                $function($value) => $this,
                default => None::create()
            }
        );
    }

    public function flatten(): OptionInterface
    {
        return $this->map(
            /**
             * @param TOption $value
             *
             * @return OptionInterface<TOption>
             */
            fn (mixed $value): OptionInterface => match (true) {
                $value instanceof SomeInterface => $value,
                default => $this
            }
        );
    }

    public function getIterator(): Generator
    {
        if ($this instanceof NoneInterface) {
            return;
        }

        $value = $this->value;

        if (is_iterable($value)) {
            yield from $value;
        } else {
            yield $value;
        }
    }

    public function isNone(): bool
    {
        return $this instanceof NoneInterface;
    }

    public function isSome(): bool
    {
        return $this instanceof SomeInterface;
    }

    public function map(callable $function): OptionInterface
    {
        if ($this instanceof NoneInterface) {
            return $this;
        }

        return Option::create($function($this->value));
    }

    public function mapOr(callable $function, mixed $fallback): mixed
    {
        if ($this instanceof NoneInterface) {
            return $fallback;
        }

        return $function($this->value);
    }

    public function mapOrElse(callable $function, callable $fallback): mixed
    {
        if ($this instanceof NoneInterface) {
            return $fallback();
        }

        return $function($this->value);
    }

    public function or(OptionInterface $option): OptionInterface
    {
        if ($this instanceof SomeInterface) {
            return $this;
        }

        return $option;
    }

    public function orElse(callable $function): OptionInterface
    {
        if ($this instanceof SomeInterface) {
            return $this;
        }

        /** @var TOption $result */
        $result = $function();

        return Option::create($result);
    }

    public function unwrap(): mixed
    {
        if ($this instanceof NoneInterface) {
            throw new NullPointerException();
        }

        return $this->value;
    }

    public function unwrapOr(mixed $fallback): mixed
    {
        if ($this instanceof SomeInterface) {
            return $this->value;
        }

        return $fallback;
    }

    public function unwrapOrElse(callable $function): mixed
    {
        if ($this instanceof SomeInterface) {
            return $this->value;
        }

        return $function();
    }
}
