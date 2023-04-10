<?php

declare(strict_types=1);

namespace Ghostwriter\Option\Contract;

use Generator;
use Ghostwriter\Option\Exception\NullPointerException;
use IteratorAggregate;
use Throwable;

/**
 * @template TOption
 *
 * @extends IteratorAggregate<int,TOption>
 */
interface OptionInterface extends IteratorAggregate
{
    /**
     * Returns None if the Option is None, otherwise returns $option.
     *
     * @template TAnd
     *
     * @param self<TAnd> $option
     *
     * @return self<TAnd|TOption>
     */
    public function and(self $option): self;

    /**
     * Returns None if the option is None, otherwise calls $function with the wrapped value and returns the result.
     *
     * @template TAndThen
     *
     * @param callable(TOption):TAndThen $function
     *
     * @return self<TAndThen|TOption>
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
     * @return TOption
     */
    public function expect(Throwable $throwable): mixed;

    /**
     * Returns None if the option is None, otherwise calls $function with the wrapped value and returns: Some(TOption) if
     * $function returns true (where TOption is the wrapped value), and None if $function returns false.
     *
     * @param callable(TOption):bool $function
     *
     * @return self<TOption>
     */
    public function filter(callable $function): self;

    /**
     * Converts from Option<Option<TOption>> to Option<TOption>. Flattening only removes one level of nesting at a time.
     *
     * @return self<TOption>
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
     * Maps a Some<TOption> to Some<TOption> by applying the callable $function to the contained value.
     *
     * @template TMap
     *
     * @param callable(TOption):TMap $function
     *
     * @return self<TMap|TOption>
     */
    public function map(callable $function): self;

    /**
     * Applies a function to the contained value (if any), or returns the provided default (if not).
     *
     * @template TFunction
     * @template TFallback
     *
     * @param callable(TOption): TFunction $function
     * @param TFallback                    $fallback
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
     * @param callable(TOption):TFunction $function
     * @param callable():TFallback        $fallback
     *
     * @return TFallback|TFunction
     */
    public function mapOrElse(callable $function, callable $fallback): mixed;

    /**
     * Returns the option if it contains a value, otherwise returns $option.
     *
     * Arguments passed to or are eagerly evaluated; if you are passing the result of a function call, it is recommended
     * to use orElse, which is lazily evaluated.
     *
     * @template TOr
     *
     * @param self<TOr> $option
     *
     * @return self<TOption|TOr>
     */
    public function or(self $option): self;

    /**
     * Returns the option if it contains a value, otherwise calls $function and returns the result.
     *
     * @template TCallableResultValue
     *
     * @param callable(): self<TCallableResultValue> $function
     *
     * @return self<TCallableResultValue|TOption>
     */
    public function orElse(callable $function): self;

    /**
     * Returns the value out of the option<TOption> if it is Some(TOption).
     *
     * @throws NullPointerException if the Option<TOption> is None
     *
     * @return TOption
     */
    public function unwrap(): mixed;

    /**
     * Returns the contained value or a default $fallback value.
     *
     * @template TFallbackValue
     *
     * @param TFallbackValue $fallback
     *
     * @return TFallbackValue|TOption
     */
    public function unwrapOr(mixed $fallback): mixed;

    /**
     * Returns the contained value or computes it from the given $function.
     *
     * @template TCallableResultValue
     *
     * @param callable():TCallableResultValue $function
     *
     * @return TCallableResultValue|TOption
     */
    public function unwrapOrElse(callable $function): mixed;
}
