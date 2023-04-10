<?php

declare(strict_types=1);

namespace Ghostwriter\Option;

use Ghostwriter\Option\Contract\NoneInterface;
use Ghostwriter\Option\Tests\Unit\NoneTest;
use Ghostwriter\Option\Traits\OptionTrait;

/**
 * @template TOption
 *
 * @implements NoneInterface<TOption>
 *
 * @see NoneTest
 */
final class None implements NoneInterface
{
    use OptionTrait;

    private static self|null $none = null;

    public static function create(): self
    {
        return self::$none ??= new self(null);
    }
}
