<?php

declare(strict_types=1);

namespace Tests\Unit\DataProvider;

use Generator;
use Ghostwriter\Option\Interface\NoneInterface;
use Ghostwriter\Option\None;

final readonly class NoneDataProvider
{
    /**
     * @return Generator<string,array{0:class-string<NoneInterface>,1:null|NoneInterface}>
     */
    public static function provide(): Generator
    {
        $none = None::new();

        foreach ([
            'NoneInterface::class' => [NoneInterface::class, $none],
            'null' => [NoneInterface::class, null],
        ] as $key => $value) {
            yield self::class . '|' . $key => $value;
        }
    }
}
