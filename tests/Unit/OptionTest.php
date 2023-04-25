<?php

declare(strict_types=1);

namespace Ghostwriter\Option\Tests\Unit;

use Generator;
use Ghostwriter\Option\AbstractOption;
use Ghostwriter\Option\None;
use Ghostwriter\Option\NoneInterface;
use Ghostwriter\Option\Option;
use Ghostwriter\Option\OptionInterface;
use Ghostwriter\Option\Some;
use Ghostwriter\Option\SomeInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Small;
use PHPUnit\Framework\TestCase;

#[CoversClass(AbstractOption::class)]
#[CoversClass(None::class)]
#[CoversClass(Option::class)]
#[CoversClass(Some::class)]
#[Small]
final class OptionTest extends TestCase
{
    /**
     * @var string
     */
    public const BLACK_LIVES_MATTER = '#BlackLivesMatter';

    /**
     * @return Generator<array-key, array{0:class-string,1:mixed}>
     */
    public static function createDataProvider(): Generator
    {
        yield self::BLACK_LIVES_MATTER => [SomeInterface::class, self::BLACK_LIVES_MATTER];
        yield 'null' => [NoneInterface::class, null];

        yield 'SomeInterface::class' => [Some::class, Some::create(self::BLACK_LIVES_MATTER)];
        yield 'NoneInterface::class' => [None::class, None::create()];
    }

    public function testNone(): void
    {
        self::assertSame(None::create(), Option::create(null));
    }

    /**
     * @template TValue
     *
     * @param class-string $expected
     * @param TValue       $value
     */
    #[DataProvider('createDataProvider')]
    public function testOptionCreate(string $expected, mixed $value): void
    {
        $option = Option::create($value);

        if ($value instanceof OptionInterface) {
            self::assertSame($value, $option);
        }

        self::assertInstanceOf($expected, $option);
    }

    public function testSome(): void
    {
        self::assertSame(self::BLACK_LIVES_MATTER, Option::create(self::BLACK_LIVES_MATTER)->unwrap());
    }
}
