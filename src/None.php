<?php

declare(strict_types=1);

namespace Ghostwriter\Option;

use Ghostwriter\Option\Contract\NoneInterface;

/**
 * @extends AbstractOption<TValue>
 *
 * @immutable
 *
 * @implements NoneInterface<TValue>
 *
 * @template TValue
 */
final class None extends AbstractOption implements NoneInterface
{
    private static ?NoneInterface $instance = null;

    public static function create(): NoneInterface
    {
        return self::$instance ??= new self(null);
    }
}
