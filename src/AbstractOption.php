<?php

declare(strict_types=1);

namespace Ghostwriter\Option;

use Closure;
use Ghostwriter\Option\Contract\NoneInterface;
use Ghostwriter\Option\Contract\OptionInterface;
use Ghostwriter\Option\Contract\SomeInterface;
use Ghostwriter\Option\Exception\InvalidReturnTypeException;
use Ghostwriter\Option\Exception\NullPointerException;
use ReflectionClass;
use ReflectionFunction;
use ReflectionNamedType;
use Throwable;
use Traversable;
use function array_key_exists;
use function is_array;
use function is_callable;

/**
 * @template TValue
 * @implements OptionInterface<TValue>
 */
abstract class AbstractOption implements OptionInterface
{
    /**
     * @var TValue
     */
    protected mixed $value;

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

        $result = $function($this->value);
        if ($result instanceof OptionInterface) {
            return $result;
        }

        throw new InvalidReturnTypeException();
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
            /**
             * @param TValue $value
             *
             * @return OptionInterface
             */
            fn (mixed $value) => true === $function($value) ?
                $this :
                None::create()
        );
    }

    public function flatten(): OptionInterface
    {
        if ($this instanceof NoneInterface) {
            return $this;
        }

        $unwrapped = $this->value;

        return $unwrapped instanceof SomeInterface ? $unwrapped : $this;
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

        return Some::create($function($this->value));
    }

    public function mapOr(mixed $fallback, callable $function): mixed
    {
        if ($this instanceof NoneInterface) {
            return $fallback;
        }

        return $function($this->value);
    }

    public function mapOrElse(callable $fallback, callable $function): mixed
    {
        if ($this instanceof NoneInterface) {
            return $fallback();
        }

        return $function($this->value);
    }

    public static function of(mixed $value, mixed $noneValue = null): OptionInterface
    {
        if ($value instanceof OptionInterface) {
            return $value;
        }

        if ($noneValue === $value) {
            return None::create();
        }

        if (null === $value) {
            return None::create();
        }

        if (is_callable($value)) {
            /** @var array{0:class-string|object,1:string}|callable $value */
            $returnType = is_array($value) ?
                (new ReflectionClass($value[0]))->getMethod($value[1])->getReturnType() :
                (new ReflectionFunction(Closure::fromCallable($value)))->getReturnType();

            if ($returnType instanceof ReflectionNamedType) {
                $returnTypeName = $returnType->getName();
                if (array_key_exists(
                    $returnTypeName,
                    [
                        OptionInterface::class=>0,
                        SomeInterface::class=>0,
                        NoneInterface::class=>0,
                    ]
                )) {
                    return None::create()->orElse(static function () use ($value): OptionInterface {
                        /** @var callable():OptionInterface<TValue> $value */
                        return $value();
                    });
                }
            }
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

        $result = $function();
        if ($result instanceof OptionInterface) {
            return $result;
        }

        throw new InvalidReturnTypeException();
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

    public function xor(OptionInterface $option): OptionInterface
    {
        if ($this instanceof SomeInterface) {
            if ($option instanceof SomeInterface) {
                return None::create();
            }

            return $this;
        }

        if ($option instanceof SomeInterface) {
            return $option;
        }

        return $this;
    }
}
