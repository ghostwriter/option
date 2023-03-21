<?php

declare(strict_types=1);

namespace Ghostwriter\Option;

use Ghostwriter\Option\Contract\NoneInterface;
use Ghostwriter\Option\Tests\Unit\NoneTest;
use Ghostwriter\Option\Traits\OptionTrait;

/**
 * @template TValue
 *
 * @implements NoneInterface<TValue>
 *
 * @see NoneTest
 */
final class None implements NoneInterface
{
    use OptionTrait;

    private static ?NoneInterface $none = null;

    public static function create(): NoneInterface
    {
        return self::$none ??= new self();
    }
}
