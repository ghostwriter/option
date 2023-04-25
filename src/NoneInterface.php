<?php

declare(strict_types=1);

namespace Ghostwriter\Option;

/**
 * @immutable
 *
 * @template TOption
 *
 * @extends OptionInterface<TOption>
 */
interface NoneInterface extends OptionInterface
{
    /** @return self<TOption> */
    public static function create(): self;
}
