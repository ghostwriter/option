<?php

declare(strict_types=1);

namespace Ghostwriter\Option\Interface;

/**
 * @template TSome
 *
 * @immutable
 *
 * @extends OptionInterface<TSome>
 */
interface SomeInterface extends OptionInterface
{
    /**
     * @template TNew
     *
     * @param TNew $value
     *
     * @return self<TNew>
     */
    public static function new(mixed $value): self;

    /**
     * @template TNullable
     *
     * @param TNullable $value
     *
     * @return OptionInterface<TNullable>
     */
    public static function nullable(mixed $value): OptionInterface;
}
