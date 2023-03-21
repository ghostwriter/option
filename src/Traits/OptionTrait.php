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
use Ghostwriter\Option\Some;
use Throwable;

/**
 * @template TValue
 */
trait OptionTrait
{
    private static ?NoneInterface $none = null;

    /**
     * @param TValue $value
     */
    private function __construct(
        private readonly mixed $value = null
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

        /** @var ?OptionInterface $result */
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
            /** @param TValue $value */
            fn (mixed $value): OptionInterface => true === $function($value) ? $this : None::create()
        );
    }

    public function flatten(): OptionInterface
    {
        return $this->map(fn (mixed $value) => $value instanceof SomeInterface ? $value : $this);
    }

    public function getIterator(): Generator
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
