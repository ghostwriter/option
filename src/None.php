<?php

declare(strict_types=1);

namespace Ghostwriter\Option;

use Ghostwriter\Option\Exception\NullPointerException;
use Ghostwriter\Option\Exception\ShouldNotHappenException;
use Ghostwriter\Option\Interface\NoneInterface;
use Ghostwriter\Option\Interface\OptionInterface;
use Override;
use Tests\Unit\NoneTest;
use Throwable;

/**
 * @template TNone of null
 *
 * @see NoneTest
 */
final class None implements NoneInterface
{
    private static ?self $instance = null;

    private function __construct() {}

    #[Override]
    public static function new(): NoneInterface
    {
        return self::$instance ??= new self();
    }

    #[Override]
    public function and(OptionInterface $option): OptionInterface
    {
        return $this;
    }

    /**
     * @throws ShouldNotHappenException
     */
    #[Override]
    public function andThen(callable $function): OptionInterface
    {
        return $this;
    }

    /** @throws Throwable */
    #[Override]
    public function expect(Throwable $throwable): mixed
    {
        throw $throwable;
    }

    /**
     * @throws ShouldNotHappenException
     */
    #[Override]
    public function filter(callable $function): OptionInterface
    {
        return $this;
    }

    /**
     * @throws NullPointerException
     */
    #[Override]
    public function get(): mixed
    {

        throw new NullPointerException();
    }

    #[Override]
    public function getOr(mixed $fallback): mixed
    {

        return $fallback;
    }

    #[Override]
    public function getOrElse(callable $function): mixed
    {

        return $function();
    }

    #[Override]
    public function is(mixed $value): bool
    {

        return null === $value;
    }

    #[Override]
    public function isNone(): bool
    {
        return true;
    }

    #[Override]
    public function isSome(): bool
    {
        return false;
    }

    #[Override]
    public function map(callable $function): OptionInterface
    {

        return $this;
    }

    #[Override]
    public function mapOr(callable $function, mixed $fallback): mixed
    {

        return $fallback;
    }

    #[Override]
    public function mapOrElse(callable $function, callable $fallback): mixed
    {
        return $fallback();
    }

    #[Override]
    public function or(OptionInterface $option): OptionInterface
    {
        return $option;
    }

    #[Override]
    public function orElse(callable $function): OptionInterface
    {
        return Option::new($function());
    }
}
