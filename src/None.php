<?php

declare(strict_types=1);

namespace Ghostwriter\Option;

/**
 * @template TNone of never
 *
 * @extends AbstractOption<TNone>
 *
 * @implements NoneInterface<TNone>
 *
 * @see \Ghostwriter\Option\Tests\Unit\NoneTest
 */
final class None extends AbstractOption implements NoneInterface
{
    private static self|null $none = null;

    /** @return self<TNone> */
    public static function create(): self
    {
        /** @var TNone $none */
        $none = null;

        return self::$none ??= new self($none);
    }
}
