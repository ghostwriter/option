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
     * @return (TNew is SomeInterface ? TNew : SomeInterface<TNew>)
     */
    public static function new(mixed $value): self;
}
