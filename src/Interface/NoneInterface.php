<?php

declare(strict_types=1);

namespace Ghostwriter\Option\Interface;

/**
 * @template TNone of null
 *
 * @immutable
 *
 * @extends OptionInterface<TNone>
 */
interface NoneInterface extends OptionInterface
{
    public static function new(): self;
}
