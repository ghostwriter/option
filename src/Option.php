<?php

declare(strict_types=1);

namespace Ghostwriter\Option;

/**
 * @see \Tests\Unit\OptionTest
 */
final class Option
{
    /**
     * @template TNone of null
     * @template TSome of mixed
     * @template TOption of TSome|TNone
     *
     * @param OptionInterface<TOption>|TOption $value
     *
     * @return OptionInterface<TOption>
     */
    public static function create(mixed $value): OptionInterface
    {
        if ($value instanceof OptionInterface) {
            /** @var OptionInterface<TOption> $value */
            return $value;
        }

        return match (true) {
            $value === null => None::create(),
            default => Some::create($value)
        };
    }
}
