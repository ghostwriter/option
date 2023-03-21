<?php

declare(strict_types=1);

namespace Ghostwriter\Option\Contract;

/**
 * @template TValue
 *
 * @extends OptionInterface<TValue>
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
