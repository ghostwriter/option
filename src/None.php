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
 * @see \Tests\Unit\NoneTest
 */
final class None extends AbstractOption implements NoneInterface
{
    private static null|self $none = null;

    /** @return self<TNone> */
    public static function create(): self
    {
        /** @var TNone $none */
        $none = null;

        return self::$none ??= new self($none);
    }
}
