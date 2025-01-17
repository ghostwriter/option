<?php

declare(strict_types=1);

namespace Ghostwriter\Option\Interface;

use Closure;
use Ghostwriter\Option\Exception\NullPointerException;
use IteratorAggregate;
use Throwable;

/**
 * @immutable
 *
 * @template TOption
 *
 * @extends IteratorAggregate<array-key,TOption>
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
     * @return (TOption is null ? self<TOption> : self<TAnd>)
     */
    public function and(self $option): self;

    /**
     * Returns None if the option is None, otherwise calls $function with the wrapped value and returns the result.
     *
     * @template TAndThen
     *
     * @param Closure(TOption):TAndThen $function
     *
     * @throws Throwable
     *
     * @return (TOption is null ? self<TOption> : self<TAndThen>)
     */
    public function andThen(Closure $function): self;

    /**
     * Returns true if the option is a Some value containing the given $value.
     *
     * @template TContainsValue
     *
     * @param TContainsValue $value
     *
     * @psalm-assert-if-true self<TContainsValue> $this
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
     * @template TFilter of bool
     *
     * @param Closure(TOption):TFilter $function
     *
     * @return (TOption is null ? NoneInterface : (TFilter is true ? SomeInterface<TOption> : NoneInterface))
     */
    public function filter(Closure $function): self;

    /**
     * Converts from Option<Option<TOption>> to Option<TOption>. Flattening only removes one level of nesting at a time.
     *
     * @return (TOption is null ? NoneInterface : (TOption is SomeInterface<TOption> ? TOption : SomeInterface<TOption>))
     */
    public function flatten(): self;

    /**
     * Returns true if the Option is an instance of None.
     *
     * @psalm-assert-if-true NoneInterface $this
     */
    public function isNone(): bool;

    /**
     * Returns true if the Option is an instance of Some.
     *
     * @psalm-assert-if-true SomeInterface<TOption> $this
     */
    public function isSome(): bool;

    /**
     * Maps a Some<TOption> to Some<TOption> by applying the Closure $function to the contained value.
     *
     * @template TMap
     *
     * @param Closure(TOption):TMap $function
     *
     * @return self<TMap|TOption>
     */
    public function map(Closure $function): self;

    /**
     * Applies a function to the contained value (if any), or returns the provided default (if not).
     *
     * @template TFunction
     * @template TFallback
     *
     * @param Closure(TOption):TFunction $function
     * @param TFallback                  $fallback
     *
     * @return TFallback|TFunction
     */
    public function mapOr(Closure $function, mixed $fallback): mixed;

    /**
     * Applies a function to the contained value (if any), or computes a default (if not).
     *
     * @template TMapOrElse
     * @template TMapOrElseFallback
     *
     * @param Closure(TOption):TMapOrElse  $function
     * @param Closure():TMapOrElseFallback $fallback
     *
     * @return TMapOrElse|TMapOrElseFallback
     */
    public function mapOrElse(Closure $function, Closure $fallback): mixed;

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
     * @template TOrElse
     *
     * @param Closure():TOrElse $function
     *
     * @return (TOption is null ? self<TOrElse> : self<TOption>)
     */
    public function orElse(Closure $function): self;

    /**
     * Returns the value out of the option<TOption> if it is Some(TOption).
     *
     * @throws NullPointerException if the Option<TOption> is None
     *
     * @return (TOption is null ? never : TOption)
     */
    public function get(): mixed;

    /**
     * Returns the contained value or a default $fallback value.
     *
     * @template TUnwrapOr
     *
     * @param TUnwrapOr $fallback
     *
     * @return (TOption is null ? TUnwrapOr : TOption)
     */
    public function getOr(mixed $fallback): mixed;

    /**
     * Returns the contained value or computes it from the given $function.
     *
     * @template TUnwrapOrElse
     *
     * @param Closure():TUnwrapOrElse $function
     *
     * @return (TOption is null ? TUnwrapOrElse : TOption)
     */
    public function getOrElse(Closure $function): mixed;
}
