<?php

declare(strict_types=1);

namespace Ghostwriter\Option\Contract;

use Ghostwriter\Option\Exception\NullPointerException;
use IteratorAggregate;
use ReflectionException;
use Throwable;
use Traversable;

/**
 * @implements IteratorAggregate<TValue>
 * @template TValue
 */
interface OptionInterface extends IteratorAggregate
{
    /**
     * Returns None if the Option is None, otherwise returns $option.
     */
    public function and(self $option): self;

    /**
     * Returns None if the option is None, otherwise calls $function with the wrapped value and returns the result.
     *
     * @param callable(TValue): OptionInterface $function
     */
    public function andThen(callable $function): self;

    /**
     * Returns true if the option is a Some value containing the given $value.
     *
     * @param TValue $value
     */
    public function contains(mixed $value): bool;

    /**
     * Returns the contained Some value, consuming the self value.
     *
     * @throws Throwable if the value is a None with a custom $throwable provided
     */
    public function expect(Throwable $throwable): mixed;

    /**
     * Returns None if the option is None, otherwise calls predicate with the wrapped value and returns: Some(TValue) if
     * $function returns true (where TValue is the wrapped value), and None if $function returns false.
     *
     * @param callable(TValue): bool $function
     */
    public function filter(callable $function): self;

    /**
     * Converts from Option<Option<TValue>> to Option<TValue>. Flattening only removes one level of nesting at a time.
     *
     * @return self<TValue>
     */
    public function flatten(): self;

    public function getIterator(): Traversable;

    /**
     * Returns true if the Option is an instance of None.
     */
    public function isNone(): bool;

    /**
     * Returns true if the Option is an instance of Some.
     */
    public function isSome(): bool;

    /**
     * Maps a Some<TValue> to Some<TValue> by applying the callable $function to the contained value.
     *
     * @param callable(TValue):TValue $function
     *
     * @return self<TValue>
     */
    public function map(callable $function): self;

    /**
     * Applies a function to the contained value (if any), or returns the provided default (if not).
     *
     * @template TFallbackValue
     *
     * @param TFallbackValue                   $fallback
     * @param callable(TValue): TFallbackValue $function
     *
     * @return TFallbackValue
     */
    public function mapOr(mixed $fallback, callable $function): mixed;

    /**
     * Applies a function to the contained value (if any), or computes a default (if not).
     *
     * @template TFallbackValue
     *
     * @param callable(): mixed      $fallback
     * @param callable(mixed): mixed $function
     */
    public function mapOrElse(callable $fallback, callable $function): mixed;

    /**
     * Creates an option with the given value.
     *
     * This is intended for consuming existing APIs and allows you to easily convert them to an option.
     *
     * By default, we treat null as the None case, and everything else as Some.
     *
     * @template TNoneValue
     *
     * @param TValue     $value     the actual return value
     * @param TNoneValue $noneValue the value which should be considered "None"; null by default
     *
     * @throws ReflectionException
     */
    public static function of(mixed $value, mixed $noneValue = null): self;

    /**
     * Returns the option if it contains a value, otherwise returns $option.
     *
     * Arguments passed to or are eagerly evaluated; if you are passing the result of a function call, it is recommended
     * to use orElse, which is lazily evaluated.
     */
    public function or(self $option): self;

    /**
     * Returns the option if it contains a value, otherwise calls $function and returns the result.
     *
     * @template TCallableResultValue
     *
     * @param callable(): OptionInterface<TCallableResultValue> $function
     */
    public function orElse(callable $function): self;

    /**
     * Returns the value out of the option<TValue> if it is Some(TValue).
     *
     * @throws NullPointerException if the Option<TValue> is None
     *
     * @return TValue
     */
    public function unwrap(): mixed;

    /**
     * Returns the contained value or a default $fallback value.
     *
     * @template TFallbackValue
     *
     * @param TFallbackValue $fallback
     *
     * @return TFallbackValue|TValue
     */
    public function unwrapOr(mixed $fallback): mixed;

    /**
     * Returns the contained value or computes it from the given $function.
     *
     * @template TCallableResultValue
     *
     * @param callable():TCallableResultValue $function
     *
     * @return TCallableResultValue|TValue
     */
    public function unwrapOrElse(callable $function): mixed;

    /**
     * Returns Some if exactly one of self, $option is Some, otherwise returns None.
     */
    public function xor(self $option): self;
}
