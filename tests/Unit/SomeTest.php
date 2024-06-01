<?php

declare(strict_types=1);

namespace Tests\Unit;

use Generator;
use Ghostwriter\Option\AbstractOption;
use Ghostwriter\Option\Exception\NullPointerException;
use Ghostwriter\Option\Exception\OptionException;
use Ghostwriter\Option\None;
use Ghostwriter\Option\Option;
use Ghostwriter\Option\OptionInterface;
use Ghostwriter\Option\Some;
use Ghostwriter\Option\SomeInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Small;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use stdClass;
use Throwable;

use function sprintf;
use function iterator_to_array;

#[CoversClass(AbstractOption::class)]
#[CoversClass(None::class)]
#[CoversClass(Option::class)]
#[CoversClass(Some::class)]
#[Small]
final class SomeTest extends TestCase
{
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
            static fn (): string => 'Testing!',
        ];
        yield 'int' => [SomeInterface::class, 42];
        yield 'float' => [SomeInterface::class, 13.37];
        yield 'object' => [SomeInterface::class, new stdClass()];
        yield 'array' => [SomeInterface::class, []];
        yield 'Some::class' => [SomeInterface::class, Some::create(1337)];
    }

    public function testAnd(): void
    {
        $some = Some::create('foo');
        $other = Some::create('foo');
        self::assertSame($some, $other->and($some));
    }

    public function testAndThen(): void
    {
        $some = Some::create('foo');
        $option = $some->andThen(static fn (mixed $x): OptionInterface => Some::create($x));

        self::assertInstanceOf(SomeInterface::class, $option);
        self::assertSame('foo', $option->unwrap());

        $this->expectException(OptionException::class);
        $option = $some->andThen(static fn (mixed $x): mixed => $x);
        self::assertSame('foo', $option->unwrap());
    }

    public function testContains(): void
    {
        $some = Some::create('foo');
        self::assertTrue($some->contains('foo'));

        self::assertFalse($some->contains(true));
    }

    public function testCreate(): void
    {
        $this->expectException(NullPointerException::class);
        Some::create(null);
    }

    /**
     *
     * @throws Throwable
     */
    public function testExpect(): void
    {
        self::assertSame('foo', Some::create('foo')->expect(new RuntimeException(__FUNCTION__)));
    }

    public function testFilter(): void
    {
        $some = Some::create('foo');
        // returns the instance if its type is Some and the given function returns true.
        self::assertSame($some, $some->filter(static fn ($x): bool => $x === 'foo'));

        // returns an instance of None if called on an instance of Some and the given function returns false.
        self::assertTrue($some->filter(static fn (string $x): bool => $x[0] === 'b')->isNone());
    }

    public function testFlatten(): void
    {
        $some = Some::create('foo');
        // unwraps Some containing a Some and returns the unwrapped Some.
        $other = Some::create($some);
        $option = $other->flatten();

        self::assertInstanceOf(SomeInterface::class, $option);
        self::assertSame('foo', $option->unwrap());

        // returns the instance if the wrapped value is not an instance of Some.
        self::assertSame('foo', $some->flatten()->unwrap());
        self::assertSame($some, $some->flatten());
    }

    public function testGetIterator(): void
    {
        $some = Some::create('foo');
        self::assertCount(1, iterator_to_array($some));

        $some = Some::create(['foo', 'bar']);
        self::assertCount(2, iterator_to_array($some));
    }

    public function testIsNone(): void
    {
        $some = Some::create('foo');
        self::assertFalse($some->isNone());
    }

    public function testIsSome(): void
    {
        $some = Some::create('foo');
        self::assertTrue($some->isSome());
    }

    public function testMap(): void
    {
        $some = Some::create('foo');
        $option = $some->map(static fn (mixed $x): string => sprintf('%s%s', $x, 'bar'));
        self::assertTrue($option->isSome());
        self::assertSame('foobar', $option->unwrap());
    }

    public function testMapOr(): void
    {
        $some = Some::create('foo');
        self::assertSame('foobar', $some->mapOr(static fn (mixed $x): string => sprintf('%s%s', $x, 'bar'), 'baz'));
    }

    public function testMapOrElse(): void
    {
        $some = Some::create('foo');

        $someFn = static fn (mixed $value): string => (string) $value;

        $noneFn = static fn (): string => 'failed!';

        self::assertSame('foo', $some->mapOrElse($someFn, $noneFn));
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
        $option = Option::create($value);

        if ($value instanceof OptionInterface) {
            self::assertSame($value, $option);
        }

        self::assertInstanceOf($expected, $option);
    }

    public function testOr(): void
    {
        $some = Some::create('foo');
        self::assertSame($some, $some->or($some));

        $some2 = Some::create('foo');
        self::assertSame($some, $some->or($some2));
    }

    public function testOrElse(): void
    {
        $some = Some::create('foo');
        self::assertSame($some, $some->orElse(static function (): never {
            throw new RuntimeException('Should not be called!');
        }));
    }

    public function testUnwrap(): void
    {
        $some = Some::create('foo');
        self::assertSame('foo', $some->unwrap());
    }

    public function testUnwrapOr(): void
    {
        $some = Some::create('foo');
        self::assertSame('foo', $some->unwrapOr('fallback'));
    }

    public function testUnwrapOrElse(): void
    {
        $some = Some::create('foo');
        self::assertSame('foo', $some->unwrapOrElse(static fn (): string => 'bar'));
    }
}
