<?php

declare(strict_types=1);

namespace Ghostwriter\Option;

/**
 * @immutable
 *
 * @template TNone of never
 *
 * @extends OptionInterface<TNone>
 */
interface NoneInterface extends OptionInterface
{
    /** @return self<TNone> */
    public static function create(): self;
}
