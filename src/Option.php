<?php

declare(strict_types=1);

namespace Ghostwriter\Option;

use Ghostwriter\Option\Contract\NoneInterface;
use Ghostwriter\Option\Contract\OptionInterface;
use Ghostwriter\Option\Contract\SomeInterface;

/** @see OptionTest */
final class Option
{
    /**
     * @template TValue
     *
     * @param TValue $value
     */
    public static function create(mixed $value): OptionInterface
    {
        return match (true) {
            $value instanceof OptionInterface => $value,
            $value === null => self::none(),
            default => self::some($value)
        };
    }

    public static function none(): NoneInterface
    {
        return None::create();
    }

    /**
     * @template TSomeValue
     *
     * @param TSomeValue $value
     */
    public static function some(mixed $value): SomeInterface
    {
        return Some::create($value);
    }
}
