<?php

declare(strict_types=1);

namespace Ghostwriter\Option\Tests\Unit;

use Generator;
use Ghostwriter\Option\Contract\NoneInterface;
use Ghostwriter\Option\Contract\OptionInterface;
use Ghostwriter\Option\Contract\SomeInterface;
use Ghostwriter\Option\Exception\NullPointerException;
use Ghostwriter\Option\None;
use Ghostwriter\Option\Some;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Throwable;

/**
 * @coversDefaultClass \Ghostwriter\Option\None
 *
 * @internal
 *
 * @small
 */
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

    /**
     * @covers \Ghostwriter\Option\None::__construct
     * @covers \Ghostwriter\Option\None::and
     * @covers \Ghostwriter\Option\None::__construct
     * @covers \Ghostwriter\Option\None::create
     * @covers \Ghostwriter\Option\Some::__construct
     * @covers \Ghostwriter\Option\Some::create
     */
    public function testAnd(): void
    {
        $some = Some::create('foobar');
        $none = None::create();
        $option = $none->and($some);

        self::assertSame($none, $option);
        self::assertInstanceOf(NoneInterface::class, $option);
    }

    /**
     * @covers \Ghostwriter\Option\None::__construct
     * @covers \Ghostwriter\Option\None::andThen
     * @covers \Ghostwriter\Option\None::unwrapOr
     * @covers \Ghostwriter\Option\None::__construct
     * @covers \Ghostwriter\Option\None::create
     */
    public function testAndThen(): void
    {
        $option = None::create()->andThen(static fn (): SomeInterface => Some::create(true));
        self::assertFalse($option->unwrapOr(false));
    }

    /**
     * @covers \Ghostwriter\Option\None::__construct
     * @covers \Ghostwriter\Option\None::contains
     * @covers \Ghostwriter\Option\None::__construct
     * @covers \Ghostwriter\Option\None::create
     */
    public function testContains(): void
    {
        $none = None::create();
        self::assertFalse($none->contains(null));
    }

    /**
     * @covers \Ghostwriter\Option\None::create
     */
    public function testCreate(): void
    {
        $none = None::create();
        self::assertSame($none, None::create());
    }

    /**
     * @covers \Ghostwriter\Option\None::__construct
     * @covers \Ghostwriter\Option\None::expect
     * @covers \Ghostwriter\Option\None::create
     *
     * @throws Throwable
     */
    public function testExpect(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(__FUNCTION__);

        None::create()->expect(new RuntimeException(__FUNCTION__));
    }

    /**
     * @covers \Ghostwriter\Option\None::__construct
     * @covers \Ghostwriter\Option\None::map
     * @covers \Ghostwriter\Option\None::filter
     * @covers \Ghostwriter\Option\None::__construct
     * @covers \Ghostwriter\Option\None::create
     */
    public function testFilter(): void
    {
        self::assertInstanceOf(NoneInterface::class, None::create()->filter(static fn ($x): bool => null === $x));
    }

    /**
     * @covers \Ghostwriter\Option\None::__construct
     * @covers \Ghostwriter\Option\None::of
     * @covers \Ghostwriter\Option\None::map
     * @covers \Ghostwriter\Option\None::flatten
     * @covers \Ghostwriter\Option\None::create
     */
    public function testFlatten(): void
    {
        $none = None::create();
        $option = $none->flatten();
        self::assertSame($none, $option);
        self::assertInstanceOf(NoneInterface::class, $option);
    }

    /**
     * @covers \Ghostwriter\Option\None::__construct
     * @covers \Ghostwriter\Option\None::getIterator
     * @covers \Ghostwriter\Option\None::create
     */
    public function testGetIterator(): void
    {
        $none = None::create();
        self::assertCount(0, $none);
    }

    /**
     * @covers \Ghostwriter\Option\None::__construct
     * @covers \Ghostwriter\Option\None::isNone
     * @covers \Ghostwriter\Option\None::create
     */
    public function testIsNone(): void
    {
        $none = None::create();
        self::assertTrue($none->isNone());
    }

    /**
     * @covers \Ghostwriter\Option\None::__construct
     * @covers \Ghostwriter\Option\None::isSome
     * @covers \Ghostwriter\Option\None::create
     */
    public function testIsSome(): void
    {
        $none = None::create();
        self::assertFalse($none->isSome());
    }

    /**
     * @covers \Ghostwriter\Option\None::__construct
     * @covers \Ghostwriter\Option\None::map
     * @covers \Ghostwriter\Option\None::create
     */
    public function testMap(): void
    {
        $none = None::create();
        self::assertInstanceOf(NoneInterface::class, $none->map(static fn (): int => 0));
    }

    /**
     * @covers \Ghostwriter\Option\None::__construct
     * @covers \Ghostwriter\Option\None::mapOr
     * @covers \Ghostwriter\Option\None::create
     */
    public function testMapOr(): void
    {
        $none = None::create();
        self::assertSame('baz', $none->mapOr(static fn (): int => 0, 'baz'));
    }

    /**
     * @covers \Ghostwriter\Option\None::__construct
     * @covers \Ghostwriter\Option\None::mapOrElse
     * @covers \Ghostwriter\Option\None::__construct
     * @covers \Ghostwriter\Option\None::create
     */
    public function testMapOrElse(): void
    {
        $none = None::create();

        $someFn = static fn (mixed $value): string => sprintf('%sbar', (string) $value);
        $noneFn = static fn (): string => 'baz';

        self::assertSame('baz', $none->mapOrElse($someFn, $noneFn));
    }

    /**
     * @covers       \Ghostwriter\Option\None::create
     * @covers       \Ghostwriter\Option\None::of
     *
     * @dataProvider ofDataProvider
     *
     * @template TValue
     *
     * @param class-string $expected
     * @param TValue       $value
     */
    public function testOf(string $expected, mixed $value): void
    {
        $option = None::of($value);

        if ($value instanceof OptionInterface) {
            self::assertSame($value, $option);
        }

        self::assertInstanceOf($expected, $option);
    }

    /**
     * @covers \Ghostwriter\Option\None::__construct
     * @covers \Ghostwriter\Option\None::or
     * @covers \Ghostwriter\Option\None::__construct
     * @covers \Ghostwriter\Option\None::create
     * @covers \Ghostwriter\Option\Some::__construct
     * @covers \Ghostwriter\Option\Some::create
     */
    public function testOr(): void
    {
        $none = None::create();
        $some = Some::create('foobar');
        self::assertSame($some, $none->or($some));
    }

    /**
     * @covers \Ghostwriter\Option\None::__construct
     * @covers \Ghostwriter\Option\None::of
     * @covers \Ghostwriter\Option\None::orElse
     * @covers \Ghostwriter\Option\None::__construct
     * @covers \Ghostwriter\Option\None::create
     * @covers \Ghostwriter\Option\Some::__construct
     * @covers \Ghostwriter\Option\Some::create
     */
    public function testOrElse(): void
    {
        $none = None::create();
        $some = Some::create('foo');
        self::assertSame($none, $none->orElse(static fn (): NoneInterface => $none));
        self::assertSame($some, $none->orElse(static fn (): SomeInterface => $some));
    }

    /**
     * @covers \Ghostwriter\Option\None::__construct
     * @covers \Ghostwriter\Option\None::unwrap
     * @covers \Ghostwriter\Option\None::__construct
     * @covers \Ghostwriter\Option\None::create
     */
    public function testUnwrap(): void
    {
        $none = None::create();
        $this->expectException(NullPointerException::class);
        $none->unwrap();
    }

    /**
     * @covers \Ghostwriter\Option\None::__construct
     * @covers \Ghostwriter\Option\None::unwrapOr
     * @covers \Ghostwriter\Option\None::__construct
     * @covers \Ghostwriter\Option\None::create
     */
    public function testUnwrapOr(): void
    {
        $none = None::create();
        self::assertSame('UnwrapOr', $none->unwrapOr('UnwrapOr'));
    }

    /**
     * @covers \Ghostwriter\Option\None::__construct
     * @covers \Ghostwriter\Option\None::unwrapOrElse
     * @covers \Ghostwriter\Option\None::__construct
     * @covers \Ghostwriter\Option\None::create
     */
    public function testUnwrapOrElse(): void
    {
        $none = None::create();
        $function = static fn (): string => 'UnwrapOrElse';

        self::assertSame('UnwrapOrElse', $none->unwrapOrElse($function));
    }
}
