<?php

declare(strict_types=1);

namespace Ghostwriter\Option\Contract;

/**
 * @immutable
 * @implements OptionInterface<TValue>
 * @template TValue
 */
interface NoneInterface extends OptionInterface
{
    /**
     * @return self<TValue>
     */
    public static function create(): self;
}
