<?php

declare(strict_types=1);

namespace Tests\Unit;

use Generator;
use Ghostwriter\Option\Exception\NullPointerException;
use Ghostwriter\Option\Exception\OptionException;
use Ghostwriter\Option\Interface\NoneInterface;
use Ghostwriter\Option\Interface\OptionInterface;
use Ghostwriter\Option\Interface\SomeInterface;
use Ghostwriter\Option\None;
use Ghostwriter\Option\Some;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Small;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use stdClass;
use Throwable;

use function iterator_to_array;
use function sprintf;

#[CoversClass(None::class)]
#[CoversClass(Some::class)]
#[Small]
final class SomeTest extends TestCase
{
    /**
     * @var string
     */
    public const string BLACK_LIVES_MATTER = '#BlackLivesMatter';

    public function testAnd(): void
    {
        $some = Some::new('foo');

        $other = Some::new('foo');

        self::assertSame($some, $other->and($some));
    }

    /**
     * @psalm-suppress DocblockTypeContradiction
     *
     * @throws Throwable
     */
    public function testAndThen(): void
    {
        $foo = 'foo';

        $some = Some::new($foo);

        $option = $some->andThen(
            /**
             * @param string $x
             *
             * @return SomeInterface<string>
             */
            static fn (mixed $x): SomeInterface => Some::new($x)
        );

        self::assertInstanceOf(SomeInterface::class, $option);

        self::assertSame($foo, $option->unwrap());

        $this->expectException(OptionException::class);

        $option = $some->andThen(static fn (mixed $x): mixed => $x);

        self::assertSame($foo, $option->unwrap());
    }

    /**
     * @throws Throwable
     */
    public function testContains(): void
    {
        $some = Some::new('foo');

        self::assertTrue($some->contains('foo'));

        self::assertFalse($some->contains(true));
    }

    /**
     * @throws Throwable
     */
    public function testCreate(): void
    {
        $this->expectException(NullPointerException::class);

        Some::new(null);
    }

    /**
     * @throws Throwable
     */
    public function testExpect(): void
    {
        self::assertSame('foo', Some::new('foo')->expect(new RuntimeException(__FUNCTION__)));
    }

    public function testFilter(): void
    {
        $some = Some::new('foo');
        // returns the instance if its type is Some and the given function returns true.
        self::assertSame($some, $some->filter(static fn ($x): bool => $x === 'foo'));

        // returns an instance of None if called on an instance of Some and the given function returns false.
        self::assertTrue($some->filter(static fn (string $x): bool => $x[0] === 'b')->isNone());
    }

    public function testFlatten(): void
    {
        $some = Some::new('foo');

        // unwraps Some containing a Some and returns the unwrapped Some.
        $option = Some::new($some)->flatten();

        self::assertInstanceOf(SomeInterface::class, $option);

        self::assertSame($some, $option);
        self::assertSame($some, $option->flatten());

        self::assertSame('foo', $some->unwrap());
        self::assertSame('foo', $option->unwrap());

        self::assertSame('foo', $some->flatten()->unwrap());
        self::assertSame('foo', $option->flatten()->unwrap());
    }

    public function testGetIterator(): void
    {
        $some = Some::new('foo');
        self::assertCount(1, iterator_to_array($some));

        $some = Some::new(['foo', 'bar']);
        self::assertCount(2, iterator_to_array($some));
    }

    public function testIsNone(): void
    {
        $some = Some::new('foo');
        self::assertFalse($some->isNone());
    }

    public function testIsSome(): void
    {
        $some = Some::new('foo');
        self::assertTrue($some->isSome());
    }

    public function testMap(): void
    {
        $some = Some::new('foo');
        $option = $some->map(
            /**
             * @param non-empty-string $x
             *
             * @return non-empty-string
             */
            static fn (mixed $x): string => sprintf('%s%s', $x, 'bar')
        );
        self::assertTrue($option->isSome());
        self::assertSame('foobar', $option->unwrap());
    }

    public function testMapOr(): void
    {
        $some = Some::new('foo');

        self::assertSame('foobar', $some->mapOr(
            /**
             * @param non-empty-string $x
             *
             * @return non-empty-string
             */
            static fn (mixed $x): string => sprintf('%s%s', $x, 'bar'),
            'baz'
        ));
    }

    public function testMapOrElse(): void
    {
        $some = Some::new('foo');

        $someFn = static fn (mixed $value): string => (string) $value;

        $noneFn = /** @return 'failed' */ static fn (): string => 'failed';

        self::assertSame('foo', $some->mapOrElse($someFn, $noneFn));
    }

    /**
     * @throws Throwable
     */
    public function testNone(): void
    {
        self::assertSame(None::new(), Some::nullable(null));
    }

    /**
     * @template TValue
     *
     * @param class-string $expected
     * @param TValue       $value
     */
    #[DataProvider('ofDataProvider')]
    public function testOptionCreate(string $expected, mixed $value): void
    {
        $option = Some::nullable($value);

        if ($value instanceof OptionInterface) {
            self::assertSame($value, $option);
        }

        self::assertInstanceOf($expected, $option);
    }

    public function testOr(): void
    {
        $some = Some::new('foo');
        self::assertSame($some, $some->or($some));

        $some2 = Some::new('foo');
        self::assertSame($some, $some->or($some2));
    }

    public function testOrElse(): void
    {
        $some = Some::new('foo');
        self::assertSame($some, $some->orElse(static function (): never {
            throw new RuntimeException('Should not be called!');
        }));
    }

    /**
     * @throws Throwable
     */
    public function testSome(): void
    {
        self::assertSame(self::BLACK_LIVES_MATTER, Some::nullable(self::BLACK_LIVES_MATTER)->unwrap());
    }

    /**
     * @template TValue
     *
     * @param class-string $expected
     * @param TValue       $value
     */
    #[DataProvider('nullableDataProvider')]
    public function testSomeNullable(string $expected, mixed $value): void
    {
        $option = Some::nullable($value);

        if ($value instanceof OptionInterface) {
            self::assertSame($value, $option);
        }

        self::assertInstanceOf($expected, $option);
    }

    public function testUnwrap(): void
    {
        $some = Some::new('foo');

        self::assertSame('foo', $some->unwrap());
    }

    public function testUnwrapOr(): void
    {
        $some = Some::new('foo');

        self::assertSame('foo', $some->unwrapOr('fallback'));
    }

    public function testUnwrapOrElse(): void
    {
        $some = Some::new('foo');
        self::assertSame('foo', $some->unwrapOrElse(/** @return 'bar' */ static fn (): string => 'bar'));
    }

    /**
     * @throws Throwable
     *
     * @return Generator<array-key, array{0:class-string,1:mixed}>
     *
     */
    public static function nullableDataProvider(): Generator
    {
        yield self::BLACK_LIVES_MATTER => [SomeInterface::class, self::BLACK_LIVES_MATTER];
        yield 'null' => [NoneInterface::class, null];

        yield 'SomeInterface::class' => [Some::class, Some::new(self::BLACK_LIVES_MATTER)];
        yield 'NoneOptionInterface::class' => [None::class, None::new()];
    }

    /**
     * @return Generator<string, array{0:class-string,1:mixed}>
     */
    public static function ofDataProvider(): Generator
    {
        yield 'true' => [SomeInterface::class, true];
        yield 'false' => [SomeInterface::class, false];
        yield 'string' => [SomeInterface::class, 'string'];
        yield 'Closure' => [
            SomeInterface::class,
/** @return 'Closure' */ static fn (): string => 'Closure',
        ];
        yield 'int' => [SomeInterface::class, 42];
        yield 'float' => [SomeInterface::class, 13.37];
        yield 'object' => [SomeInterface::class, new stdClass()];
        yield 'array' => [SomeInterface::class, []];
        yield 'Some::class' => [SomeInterface::class, Some::new(1337)];
    }
}
