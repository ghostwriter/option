<?php

declare(strict_types=1);

namespace Ghostwriter\Option;

use Ghostwriter\Option\Contract\NoneInterface;
use Ghostwriter\Option\Traits\OptionTrait;

/**
 * @immutable
 *
 * @implements NoneInterface<TValue>
 *
 * @template TValue
 */
final class None implements NoneInterface
{
    use OptionTrait;

    private static ?NoneInterface $instance = null;

    public static function create(): NoneInterface
    {
        return self::$instance ??= new self();
    }
}
