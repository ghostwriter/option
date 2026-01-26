<?php

declare(strict_types=1);

namespace Ghostwriter\Option;

use Ghostwriter\Option\Exception\NullPointerException;
use Ghostwriter\Option\Exception\ShouldNotHappenException;
use Ghostwriter\Option\Interface\NoneInterface;
use Ghostwriter\Option\Interface\OptionInterface;
use Ghostwriter\Option\Interface\SomeInterface;
use Override;
use Tests\Unit\SomeTest;
use Throwable;

use function get_debug_type;
use function sprintf;

/**
 * @template TSome
 *
 * @implements SomeInterface<TSome>
 *
 * @see SomeTest
 */
final readonly class Some implements SomeInterface
{
    /** @param TSome $value */
    private function __construct(
        private mixed $value
    ) {}

    /**
     * @template TNew
     *
     * @param TNew $value
     *
     * @throws NullPointerException
     *
     * @return (TNew is SomeInterface ? TNew : SomeInterface<TNew>)
     */
    #[Override]
    public static function new(mixed $value): SomeInterface
    {
        return match (true) {
            $value instanceof NoneInterface, null === $value => throw new NullPointerException(),

            $value instanceof SomeInterface => $value,

            default => new self($value),
        };
    }

    #[Override]
    public function and(OptionInterface $option): OptionInterface
    {
        return $option;
    }

    /**
     * @throws ShouldNotHappenException
     */
    #[Override]
    public function andThen(callable $function): OptionInterface
    {
        /** @var null|OptionInterface<TSome> $result */
        $result = $function($this->value);

        if ($result instanceof OptionInterface) {
            return $result;
        }

        throw new ShouldNotHappenException(sprintf(
            'Callable passed to andThen() must return an instance of %s.',
            OptionInterface::class
        ));
    }

    /** @throws Throwable */
    #[Override]
    public function expect(Throwable $throwable): mixed
    {
        return $this->value;
    }

    /**
     * @throws ShouldNotHappenException
     */
    #[Override]
    public function filter(callable $function): OptionInterface
    {
        $result = $function($this->value);

        return match ($result) {
            true => $this,
            false => None::new(),
            default => throw new ShouldNotHappenException(sprintf(
                'Callable passed to filter() must return a boolean, %s given.',
                get_debug_type($result)
            )),
        };
    }

    /**
     * @throws NullPointerException
     */
    #[Override]
    public function get(): mixed
    {
        return $this->value;
    }

    #[Override]
    public function getOr(mixed $fallback): mixed
    {
        return $this->value;
    }

    #[Override]
    public function getOrElse(callable $function): mixed
    {
        return $this->value;
    }

    #[Override]
    public function is(mixed $value): bool
    {
        return $this->value === $value;
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
    public function map(callable $function): OptionInterface
    {
        return Option::new($function($this->value));
    }

    #[Override]
    public function mapOr(callable $function, mixed $fallback): mixed
    {
        return $function($this->value);
    }

    #[Override]
    public function mapOrElse(callable $function, callable $fallback): mixed
    {
        return $function($this->value);
    }

    #[Override]
    public function or(OptionInterface $option): OptionInterface
    {
        return $this;
    }

    #[Override]
    public function orElse(callable $function): OptionInterface
    {
        return $this;
    }
}
