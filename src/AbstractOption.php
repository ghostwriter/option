<?php

declare(strict_types=1);

namespace Ghostwriter\Option;

use Ghostwriter\Option\Contract\NoneInterface;
use Ghostwriter\Option\Contract\OptionInterface;
use Ghostwriter\Option\Contract\SomeInterface;
use Ghostwriter\Option\Exception\NullPointerException;
use Throwable;
use Traversable;

/**
 * @template TValue
 * @immutable
 * @implements OptionInterface<TValue>
 */
abstract class AbstractOption implements OptionInterface
{
    /**
     * @param TValue $value
     */
    protected function __construct(protected mixed $value)
    {
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

        return self::of($function($this->value));
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
        return $this->andThen(
            /** @param TValue $value */
            fn (mixed $value): OptionInterface => $function($value) ? $this : None::create()
        );
    }

    public function flatten(): OptionInterface
    {
        return $this->andThen(fn (mixed $value) => $value instanceof SomeInterface ? $value : $this);
    }

    public function getIterator(): Traversable
    {
        if ($this instanceof SomeInterface) {
            yield $this->value;
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

        return self::of($function($this->value));
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

    public static function of(mixed $value): OptionInterface
    {
        if (null === $value) {
            return None::create();
        }

        if ($value instanceof OptionInterface) {
            return $value;
        }

        return Some::create($value);
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

        return self::of($function());
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
