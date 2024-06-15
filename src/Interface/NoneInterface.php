<?php

declare(strict_types=1);

namespace Ghostwriter\Option\Interface;

/**
 * @immutable
 *
 * @extends OptionInterface<null>
 */
interface NoneInterface extends OptionInterface
{
    public static function new(): self;
}
