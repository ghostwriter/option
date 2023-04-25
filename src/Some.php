<?php

declare(strict_types=1);

namespace Ghostwriter\Option;

use Ghostwriter\Option\Exception\NullPointerException;
use Ghostwriter\Option\Tests\Unit\SomeTest;

/**
 * @template TSome
 *
 * @extends AbstractOption<TSome>
 *
 * @implements SomeInterface<TSome>
 *
 * @see SomeTest
 */
final class Some extends AbstractOption implements SomeInterface
{
    /**
     * @template TValue
     *
     * @param TValue $value
     *
     * @return self<TValue>
     */
    public static function create(mixed $value): self
    {
        if ($value === null) {
            throw new NullPointerException();
        }

        return new self($value);
    }
}
