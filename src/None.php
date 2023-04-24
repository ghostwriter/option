<?php

declare(strict_types=1);

namespace Ghostwriter\Option;

use Ghostwriter\Option\Contract\NoneInterface;
use Ghostwriter\Option\Tests\Unit\NoneTest;

/**
 * @template TNone of null
 *
 * @extends AbstractOption<TNone>
 *
 * @implements NoneInterface<TNone>
 *
 * @see NoneTest
 */
final class None extends AbstractOption implements NoneInterface
{
    private static self|null $none = null;

    /** @return self<TNone> */
    public static function create(): self
    {
        return self::$none ??= new self(null);
    }
}
