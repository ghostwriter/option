<?php

declare(strict_types=1);

namespace Tests\Unit\DataProvider;

use Closure;
use Generator;
use Ghostwriter\Option\Interface\SomeInterface;
use Ghostwriter\Option\Some;
use stdClass;
use Throwable;

final readonly class SomeDataProvider
{
    private const string BLACK_LIVES_MATTER = '#BlackLivesMatter';

    /**
     * @throws Throwable
     *
     * @return Generator<string,array{0:class-string,1:mixed}>
     */
    public static function provide(): Generator
    {
        foreach ([
            'array' => [SomeInterface::class, []],
            'closure' => [
                SomeInterface::class,
                static fn (): string => Closure::class,
            ],
            'false' => [SomeInterface::class, false],
            'float' => [SomeInterface::class, 13.37],
            'int' => [SomeInterface::class, 42],
            'object' => [SomeInterface::class, new stdClass()],
            'SomeInterface::class' => [SomeInterface::class, Some::new(self::BLACK_LIVES_MATTER)],
            'string' => [SomeInterface::class, self::BLACK_LIVES_MATTER],
            'true' => [SomeInterface::class, true],
        ] as $key => $value) {
            yield self::class . '|' . $key => $value;
        }
    }
}
