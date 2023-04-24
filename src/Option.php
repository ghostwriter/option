<?php

declare(strict_types=1);

namespace Ghostwriter\Option;

use Ghostwriter\Option\Contract\OptionInterface;

/** @see OptionTest */
final class Option
{
    /**
     * @template TOption
     *
     * @param TOption $value
     *
     * @return OptionInterface<TOption>
     */
    public static function create(mixed $value): OptionInterface
    {
        return match (true) {
            $value instanceof OptionInterface => $value,
            $value === null => None::create(),
            default => Some::create($value)
        };
    }
}
