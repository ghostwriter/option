<?php

declare(strict_types=1);

namespace Ghostwriter\Option;

use Closure;
use Generator;
use Ghostwriter\Option\Exception\NullPointerException;
use Ghostwriter\Option\Exception\OptionException;
use Ghostwriter\Option\Interface\NoneInterface;
use Ghostwriter\Option\Interface\OptionInterface;
use Override;
use Throwable;

/**
 * @see \Tests\Unit\NoneTest
 */
final class None implements NoneInterface
{
    private static ?self $instance = null;

    private function __construct()
    {
    }

    #[Override]
    public function and(OptionInterface $option): self
    {
        return $this;
    }

    /**
     * @throws OptionException
     */
    #[Override]
    public function andThen(Closure $function): self
    {
        return $this;
    }

    #[Override]
    public function contains(mixed $value): bool
    {
        return false;
    }

    /**
     * @throws Throwable
     */
    #[Override]
    public function expect(Throwable $throwable): mixed
    {
        throw $throwable;
    }

    #[Override]
    public function filter(Closure $function): self
    {
        return $this;
    }

    #[Override]
    public function flatten(): self
    {
        return $this;
    }

    #[Override]
    public function getIterator(): Generator
    {
        yield from [];
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
    public function map(Closure $function): OptionInterface
    {
        return $this;
    }

    #[Override]
    public function mapOr(Closure $function, mixed $fallback): mixed
    {
        return $fallback;
    }

    #[Override]
    public function mapOrElse(Closure $function, Closure $fallback): mixed
    {
        return $fallback();
    }

    #[Override]
    public function or(OptionInterface $option): OptionInterface
    {
        return $option;
    }

    #[Override]
    public function orElse(Closure $function): OptionInterface
    {
        $value = $function();

        return match (true) {
            $value instanceof OptionInterface => $value,
            $value === null => self::new(),
            default => Some::new($value),
        };
    }

    /**
     * @throws NullPointerException
     */
    #[Override]
    public function unwrap(): mixed
    {
        throw new NullPointerException();
    }

    #[Override]
    public function unwrapOr(mixed $fallback): mixed
    {
        return $fallback;
    }

    #[Override]
    public function unwrapOrElse(Closure $function): mixed
    {
        return $function();
    }

    #[Override]
    public static function new(): NoneInterface
    {
        return self::$instance ??= new self();
    }
}
