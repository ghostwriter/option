<?php

declare(strict_types=1);

namespace Ghostwriter\Option;

use Ghostwriter\Option\Interface\OptionInterface;

final readonly class Option
{
    public static function new(mixed $value): OptionInterface
    {
        return match (true) {
            null === $value => None::new(),

            $value instanceof OptionInterface => $value,

            default => Some::new($value),
        };
    }
}
