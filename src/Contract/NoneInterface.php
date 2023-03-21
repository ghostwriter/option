<?php

declare(strict_types=1);

namespace Ghostwriter\Option\Contract;

/**
 * @template TValue
 *
 * @extends OptionInterface<TValue>
 */
interface NoneInterface extends OptionInterface
{
    /** @return self<TValue> */
    public static function create(): self;
}
