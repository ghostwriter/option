<?php

declare(strict_types=1);

namespace Ghostwriter\Option\Contract;

use Generator;
use Ghostwriter\Option\Exception\NullPointerException;
use IteratorAggregate;
use Throwable;

/**
 * @template TValue
 *
 * @extends IteratorAggregate<int,TValue>
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
     * @template TAndThen
     *
     * @param callable(TValue):TAndThen $function
     */
    public function andThen(callable $function): self;

    /**
     * Returns true if the option is a Some value containing the given $value.
     *
     * @template TContainsValue
     *
     * @param TContainsValue $value
     */
    public function contains(mixed $value): bool;

    /**
     * Returns the contained Some value, consuming the self value.
     *
     * @throws Throwable if the value is a None with a custom $throwable provided
     *
     * @return TValue
     */
    public function expect(Throwable $throwable): mixed;

    /**
     * Returns None if the option is None, otherwise calls $function with the wrapped value and returns: Some(TValue) if
     * $function returns true (where TValue is the wrapped value), and None if $function returns false.
     *
     * @param callable(TValue):bool $function
     *
     * @return self<TValue>
     */
    public function filter(callable $function): self;

    /**
     * Converts from Option<Option<TValue>> to Option<TValue>. Flattening only removes one level of nesting at a time.
     *
     * @return self<TValue>
     */
    public function flatten(): self;

    public function getIterator(): Generator;

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
     * @template TMap
     *
     * @param callable(TValue):TMap $function
     *
     * @return self<TMap>
     */
    public function map(callable $function): self;

    /**
     * Applies a function to the contained value (if any), or returns the provided default (if not).
     *
     * @template TFunction
     * @template TFallback
     *
     * @param callable(TValue): TFunction $function
     * @param TFallback                   $fallback
     *
     * @return TFallback|TFunction
     */
    public function mapOr(callable $function, mixed $fallback): mixed;

    /**
     * Applies a function to the contained value (if any), or computes a default (if not).
     *
     * @template TFunction
     * @template TFallback
     *
     * @param callable(TValue):TFunction $function
     * @param callable():TFallback       $fallback
     *
     * @return TFallback|TFunction
     */
    public function mapOrElse(callable $function, callable $fallback): mixed;

    /**
     * Creates an option with the given value.
     *
     * By default, we treat null as the None case, and everything else as Some.
     *
     * @template TNullableValue
     *
     * @param TNullableValue $value the actual value
     *
     * @return self<TNullableValue|TValue>
     */
    public static function of(mixed $value): self;

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
     * @param callable(): self<TCallableResultValue> $function
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
}
