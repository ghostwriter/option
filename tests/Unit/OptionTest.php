<?php

declare(strict_types=1);

namespace Tests\Unit;

use Ghostwriter\Option\None;
use Ghostwriter\Option\Option;
use Ghostwriter\Option\Some;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProviderExternal;
use Tests\Unit\DataProvider\NoneDataProvider;
use Tests\Unit\DataProvider\SomeDataProvider;
use Throwable;

#[CoversClass(None::class)]
#[CoversClass(Option::class)]
#[CoversClass(Some::class)]
final class OptionTest extends AbstractTestCase
{
    /**
     * @template TValue
     *
     * @param class-string $expected
     * @param TValue       $value
     *
     * @throws Throwable
     */
    #[DataProviderExternal(SomeDataProvider::class, 'provide')]
    #[DataProviderExternal(NoneDataProvider::class, 'provide')]
    public function testNew(string $expected, mixed $value): void
    {
        self::assertInstanceOf($expected, Option::new($value));
    }
}
