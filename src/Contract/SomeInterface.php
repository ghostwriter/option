<?php

declare(strict_types=1);

namespace Ghostwriter\Option\Contract;

/**
 * @immutable
 *
 * @implements OptionInterface<TValue>
 *
 * @template TValue
 */
interface SomeInterface extends OptionInterface
{
    /**
     * @template TSomeValue
     *
     * @param TSomeValue $value
     *
     * @return self<TSomeValue>
     */
    public static function create(mixed $value): self;
}
