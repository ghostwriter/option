<?php

declare(strict_types=1);

namespace Ghostwriter\Option;

/**
 * @immutable
 *
 * @template TSome
 *
 * @extends OptionInterface<TSome>
 */
interface SomeInterface extends OptionInterface
{
    /**
     * @template TValue
     *
     * @param TValue $value
     *
     * @return self<TValue>
     */
    public static function create(mixed $value): self;
}
