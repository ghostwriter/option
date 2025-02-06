<?php

declare(strict_types=1);

namespace Ghostwriter\Option\Interface;

use Ghostwriter\Option\Exception\NullPointerException;
use Throwable;

/**
 * @immutable
 *
 * @template TOption
 */
interface OptionInterface
{
    /**
     * @template TAnd
     *
     * @param self<TAnd> $option
     *
     * @return (TOption is null ? self<TOption> : self<TAnd>)
     */
    public function and(self $option): self;

    /**
     * @template TAndThen
     *
     * @param callable(TOption):TAndThen $function
     *
     * @throws Throwable
     *
     * @return (TOption is null ? self<TOption> : self<TAndThen>)
     */
    public function andThen(callable $function): self;

    /**
     * @throws Throwable if the value is a None with a custom $throwable provided
     *
     * @return (TOption is null ? never : TOption)
     */
    public function expect(Throwable $throwable): mixed;

    /**
     * @template TFilter of bool
     *
     * @param callable(TOption):TFilter $function
     *
     * @return NoneInterface
     */
    public function filter(callable $function): self;

    /**
     * @throws NullPointerException if the Option<TOption> is None
     *
     * @return (TOption is null ? never : TOption)
     */
    public function get(): mixed;

    /**
     * @template TGetOr
     *
     * @param TGetOr $fallback
     *
     * @return (TOption is null ? TGetOr : TOption)
     */
    public function getOr(mixed $fallback): mixed;

    /**
     * @template TGetOrElse
     *
     * @param callable():TGetOrElse $function
     *
     * @return (TOption is null ? TGetOrElse : TOption)
     */
    public function getOrElse(callable $function): mixed;

    /**
     * @template TIsValue
     *
     * @param TIsValue $value
     *
     * @return (TOption is null ? false : bool)
     */
    public function is(mixed $value): bool;

    /**
     * @return (TOption is null ? true : false)
     */
    public function isNone(): bool;

    /**
     * @return (TOption is null ? false : true)
     */
    public function isSome(): bool;

    /**
     * @template TMap
     *
     * @param callable(TOption):TMap $function
     *
     * @return NoneInterface
     */
    public function map(callable $function): self;

    /**
     * @template TFunction
     * @template TFallback
     *
     * @param callable(TOption):TFunction $function
     * @param TFallback                   $fallback
     *
     * @return (TOption is null ? TFallback : TFunction)
     */
    public function mapOr(callable $function, mixed $fallback): mixed;

    /**
     * @template TMapOrElse
     * @template TMapOrElseFallback
     *
     * @param callable(TOption):TMapOrElse  $function
     * @param callable():TMapOrElseFallback $fallback
     *
     * @return (TOption is null ? TMapOrElseFallback : TMapOrElse)
     */
    public function mapOrElse(callable $function, callable $fallback): mixed;

    /**
     * @template TOr
     *
     * @param self<TOr> $option
     *
     * @return NoneInterface
     */
    public function or(self $option): self;

    /**
     * @template TOrElse
     *
     * @param callable():TOrElse $function
     *
     * @return SomeInterface
     */
    public function orElse(callable $function): self;
}
