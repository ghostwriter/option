<?php

declare(strict_types=1);

namespace Ghostwriter\Option;

/**
 * @immutable
 *
 * @template TNone of null
 *
 * @extends OptionInterface<TNone>
 */
interface NoneInterface extends OptionInterface
{
    /** @return self<TNone> */
    public static function create(): self;
}
