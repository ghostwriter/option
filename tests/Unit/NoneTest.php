<?php

declare(strict_types=1);

namespace Ghostwriter\Option\Tests\Unit;

use Generator;
use Ghostwriter\Option\Contract\NoneInterface;
use Ghostwriter\Option\Contract\OptionInterface;
use Ghostwriter\Option\Contract\SomeInterface;
use Ghostwriter\Option\Exception\NullPointerException;
use Ghostwriter\Option\None;
use Ghostwriter\Option\Option;
use Ghostwriter\Option\Some;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Small;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Throwable;

#[CoversClass(None::class)]
#[CoversClass(Option::class)]
#[CoversClass(Some::class)]
#[Small]
final class NoneTest extends TestCase
{
    /**
     * @return Generator<array-key, array{0:class-string,1:mixed}>
     */
    public static function ofDataProvider(): Generator
    {
        yield 'null' => [NoneInterface::class, null];
        yield 'None::class' => [NoneInterface::class, None::create()];
    }

    public function testAnd(): void
    {
        $some = Some::create('foobar');
        $none = None::create();
        $option = $none->and($some);

        self::assertSame($none, $option);
        self::assertInstanceOf(NoneInterface::class, $option);
    }

    public function testAndThen(): void
    {
        $option = None::create()->andThen(static fn (): SomeInterface => Some::create(true));
        self::assertFalse($option->unwrapOr(false));
    }

    public function testContains(): void
    {
        $none = None::create();
        self::assertFalse($none->contains(null));
    }

    public function testCreate(): void
    {
        $none = None::create();
        self::assertSame($none, None::create());
    }

    /**
     *
     * @throws Throwable
     */
    public function testExpect(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(__FUNCTION__);

        None::create()->expect(new RuntimeException(__FUNCTION__));
    }

    public function testFilter(): void
    {
        self::assertInstanceOf(NoneInterface::class, None::create()->filter(static fn (mixed $x): bool => $x === null));
    }

    public function testFlatten(): void
    {
        $none = None::create();
        $option = $none->flatten();
        self::assertSame($none, $option);
        self::assertInstanceOf(NoneInterface::class, $option);
    }

    public function testGetIterator(): void
    {
        $none = None::create();
        self::assertCount(0, iterator_to_array($none));
    }

    public function testIsNone(): void
    {
        $none = None::create();
        self::assertTrue($none->isNone());
    }

    public function testIsSome(): void
    {
        $none = None::create();
        self::assertFalse($none->isSome());
    }

    public function testMap(): void
    {
        $none = None::create();
        self::assertInstanceOf(NoneInterface::class, $none->map(static fn (): int => 0));
    }

    public function testMapOr(): void
    {
        $none = None::create();
        self::assertSame('baz', $none->mapOr(static fn (): int => 0, 'baz'));
    }

    public function testMapOrElse(): void
    {
        $none = None::create();

        $someFn = static fn (mixed $value): string => sprintf('%sbar', (string) $value);
        $noneFn = static fn (): string => 'baz';

        self::assertSame('baz', $none->mapOrElse($someFn, $noneFn));
    }

    /**
     * @dataProvider ofDataProvider
     *
     * @template TValue
     *
     * @param class-string $expected
     * @param TValue       $value
     */
    public function testOptionCreate(string $expected, mixed $value): void
    {
        $option = Option::create($value);

        if ($value instanceof OptionInterface) {
            self::assertSame($value, $option);
        }

        self::assertInstanceOf($expected, $option);
    }

    public function testOr(): void
    {
        $none = None::create();
        $some = Some::create('foobar');
        self::assertSame($some, $none->or($some));
    }

    public function testOrElse(): void
    {
        $none = None::create();
        $some = Some::create('foo');
        self::assertSame($none, $none->orElse(static fn (): NoneInterface => $none));
        self::assertSame($some, $none->orElse(static fn (): SomeInterface => $some));
    }

    public function testUnwrap(): void
    {
        $none = None::create();
        $this->expectException(NullPointerException::class);
        $none->unwrap();
    }

    public function testUnwrapOr(): void
    {
        $none = None::create();
        self::assertSame('UnwrapOr', $none->unwrapOr('UnwrapOr'));
    }

    public function testUnwrapOrElse(): void
    {
        $none = None::create();
        $function = static fn (): string => 'UnwrapOrElse';

        self::assertSame('UnwrapOrElse', $none->unwrapOrElse($function));
    }
}
