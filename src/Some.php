<?php

declare(strict_types=1);

namespace Ghostwriter\Option;

use Ghostwriter\Option\Contract\SomeInterface;
use Ghostwriter\Option\Exception\NullPointerException;

/**
 * @extends AbstractOption<TValue>
 * @immutable
 * @implements SomeInterface<TValue>
 * @template TValue
 */
final class Some extends AbstractOption implements SomeInterface
{
    /**
     * @param TValue $value
     */
    private function __construct(mixed $value)
    {
        // Singleton
        $this->value = $value;
    }

    /**
     * @template TNoneValue
     *
     * @param TValue          $value
     * @param null|TNoneValue $noneValue
     */
    public static function create(mixed $value, mixed $noneValue = null): SomeInterface
    {
        if (null === $value) {
            throw new NullPointerException();
        }

        if ($noneValue === $value) {
            throw new NullPointerException();
        }

        return new self($value);
    }
}
