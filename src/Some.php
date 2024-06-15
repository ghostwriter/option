<?php

declare(strict_types=1);

namespace Ghostwriter\Option;

use Closure;
use Generator;
use Ghostwriter\Option\Exception\NullPointerException;
use Ghostwriter\Option\Exception\OptionException;
use Ghostwriter\Option\Interface\NoneInterface;
use Ghostwriter\Option\Interface\OptionInterface;
use Ghostwriter\Option\Interface\SomeInterface;
use Override;
use Throwable;

use function is_iterable;
use function sprintf;

/**
 * @template TSome
 *
 * @implements SomeInterface<TSome>
 *
 * @see \Tests\Unit\SomeTest
 */
final readonly class Some implements SomeInterface
{
    /**
     * @param TSome $value
     */
    public function __construct(
        private mixed $value
    ) {
    }

    #[Override]
    public function and(OptionInterface $option): OptionInterface
    {
        return $option;
    }

    #[Override]
    public function andThen(Closure $function): OptionInterface
    {
        /** @var null|OptionInterface<TSome> $result */
        $result = $function($this->value);

        if ($result instanceof OptionInterface) {
            return $result;
        }

        throw new OptionException(sprintf(
            'Closure passed to andThen() must return an instance of %s.',
            OptionInterface::class
        ));
    }

    #[Override]
    public function contains(mixed $value): bool
    {
        if ($this instanceof NoneInterface) {
            return false;
        }

        return $this->value === $value;
    }

    /**
     * @throws Throwable
     */
    #[Override]
    public function expect(Throwable $throwable): mixed
    {
        if ($this instanceof NoneInterface) {
            throw $throwable;
        }

        return $this->value;
    }

    #[Override]
    public function filter(Closure $function): OptionInterface
    {
        return match (true) {
            $function($this->value) => $this,
            default => None::new()
        };
    }

    #[Override]
    public function flatten(): OptionInterface
    {
        return match (true) {
            $this->value instanceof SomeInterface => $this->value,
            default => $this
        };
    }

    #[Override]
    public function getIterator(): Generator
    {
        $value = $this->value;

        if (is_iterable($value)) {
            yield from $value;
        } else {
            yield $value;
        }
    }

    #[Override]
    public function isNone(): bool
    {
        return false;
    }

    #[Override]
    public function isSome(): bool
    {
        return true;
    }

    #[Override]
    public function map(Closure $function): OptionInterface
    {
        $value = $function($this->value);

        return match (true) {
            $value instanceof OptionInterface => $value,

            $value === null => None::new(),

            default => self::new($value),
        };
    }

    #[Override]
    public function mapOr(Closure $function, mixed $fallback): mixed
    {
        return $function($this->value);
    }

    #[Override]
    public function mapOrElse(Closure $function, Closure $fallback): mixed
    {
        return $function($this->value);
    }

    #[Override]
    public function or(OptionInterface $option): OptionInterface
    {
        return $this;
    }

    #[Override]
    public function orElse(Closure $function): OptionInterface
    {
        return $this;
    }

    #[Override]
    public function unwrap(): mixed
    {
        return $this->value;
    }

    #[Override]
    public function unwrapOr(mixed $fallback): mixed
    {
        return $this->value;
    }

    #[Override]
    public function unwrapOrElse(Closure $function): mixed
    {
        return $this->value;
    }

    /**
     * @template TNew
     *
     * @param TNew $value
     *
     * @throws NullPointerException
     *
     * @return SomeInterface<TNew>
     */
    #[Override]
    public static function new(mixed $value): SomeInterface
    {
        return match (true) {
            $value === null => throw new NullPointerException(),

            default => /** @var SomeInterface<TNew> */ new self($value),
        };
    }

    #[Override]
    public static function nullable(mixed $value): OptionInterface
    {
        return match (true) {
            $value === null => None::new(),

            default => self::new($value),
        };
    }
}
