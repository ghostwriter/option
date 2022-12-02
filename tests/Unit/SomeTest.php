<?php

declare(strict_types=1);

namespace Ghostwriter\Option\Tests\Unit;

use Ghostwriter\Option\Contract\OptionInterface;
use Ghostwriter\Option\Contract\SomeInterface;
use Ghostwriter\Option\Exception\NullPointerException;
use Ghostwriter\Option\None;
use Ghostwriter\Option\Some;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use stdClass;
use Throwable;
use Traversable;
use function sprintf;

/**
 * @coversDefaultClass \Ghostwriter\Option\Some
 *
 * @internal
 *
 * @small
 */
final class SomeTest extends TestCase
{
    private SomeInterface $some;

    protected function setUp(): void
    {
        $this->some = Some::create('foo');
    }

    /**
     * @return Traversable<array-key, array{0:class-string,1:mixed}>
     */
    public function ofDataProvider(): Traversable
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

    /**
     * @covers \Ghostwriter\Option\Some::__construct
     * @covers \Ghostwriter\Option\Some::and
     * @covers \Ghostwriter\Option\Some::create
     * @covers \Ghostwriter\Option\Some::__construct
     * @covers \Ghostwriter\Option\Some::create
     */
    public function testAnd(): void
    {
        $some = Some::create('foo');
        self::assertSame($some, $this->some->and($some));
    }

    /**
     * @covers \Ghostwriter\Option\Some::__construct
     * @covers \Ghostwriter\Option\Some::of
     * @covers \Ghostwriter\Option\Some::andThen
     * @covers \Ghostwriter\Option\Some::unwrap
     * @covers \Ghostwriter\Option\Some::__construct
     * @covers \Ghostwriter\Option\Some::create
     */
    public function testAndThen(): void
    {
        $option = $this->some->andThen(static fn (mixed $x): OptionInterface => Some::create($x));

        self::assertInstanceOf(SomeInterface::class, $option);
        self::assertSame('foo', $option->unwrap());

        $this->expectException(RuntimeException::class);
        $option = $this->some->andThen(static fn (mixed $x): mixed => $x);
        self::assertSame('foo', $option->unwrap());
    }

    /**
     * @covers \Ghostwriter\Option\Some::__construct
     * @covers \Ghostwriter\Option\Some::contains
     * @covers \Ghostwriter\Option\Some::__construct
     * @covers \Ghostwriter\Option\Some::create
     */
    public function testContains(): void
    {
        self::assertTrue($this->some->contains('foo'));

        self::assertFalse($this->some->contains(true));
    }

    /**
     * @covers \Ghostwriter\Option\Some::__construct
     * @covers \Ghostwriter\Option\Some::__construct
     * @covers \Ghostwriter\Option\Some::create
     */
    public function testCreate(): void
    {
        $this->expectException(NullPointerException::class);
        Some::create(null);
    }

    /**
     * @covers \Ghostwriter\Option\Some::__construct
     * @covers \Ghostwriter\Option\Some::expect
     * @covers \Ghostwriter\Option\Some::__construct
     * @covers \Ghostwriter\Option\Some::create
     *
     * @throws Throwable
     */
    public function testExpect(): void
    {
        self::assertSame('foo', $this->some->expect(new RuntimeException('Expect')));
    }

    /**
     * @covers \Ghostwriter\Option\Some::__construct
     * @covers \Ghostwriter\Option\Some::of
     * @covers \Ghostwriter\Option\Some::map
     * @covers \Ghostwriter\Option\Some::filter
     * @covers \Ghostwriter\Option\Some::isNone
     * @covers \Ghostwriter\Option\None::create
     * @covers \Ghostwriter\Option\Some::__construct
     * @covers \Ghostwriter\Option\Some::create
     */
    public function testFilter(): void
    {
        // returns the instance if its type is Some and the given function returns true.
        self::assertSame($this->some, $this->some->filter(static fn ($x): bool => 'foo' === $x));

        // returns an instance of None if called on an instance of Some and the given function returns false.
        self::assertTrue($this->some->filter(static fn ($x): bool => 'bar' === $x)->isNone());
    }

    /**
     * @covers \Ghostwriter\Option\Some::__construct
     * @covers \Ghostwriter\Option\Some::map
     * @covers \Ghostwriter\Option\Some::of
     * @covers \Ghostwriter\Option\Some::flatten
     * @covers \Ghostwriter\Option\Some::unwrap
     * @covers \Ghostwriter\Option\Some::__construct
     * @covers \Ghostwriter\Option\Some::create
     */
    public function testFlatten(): void
    {
        // unwraps Some containing a Some and returns the unwrapped Some.
        $some = Some::create($this->some);
        $option = $some->flatten();

        self::assertInstanceOf(SomeInterface::class, $option);
        self::assertSame('foo', $option->unwrap());

        // returns the instance if the wrapped value is not an instance of Some.
        self::assertSame('foo', $this->some->flatten()->unwrap());
        self::assertSame($this->some, $this->some->flatten());
    }

    /**
     * @covers \Ghostwriter\Option\Some::__construct
     * @covers \Ghostwriter\Option\Some::getIterator
     * @covers \Ghostwriter\Option\Some::__construct
     * @covers \Ghostwriter\Option\Some::create
     */
    public function testGetIterator(): void
    {
        self::assertCount(1, $this->some);
    }

    /**
     * @covers \Ghostwriter\Option\Some::__construct
     * @covers \Ghostwriter\Option\Some::isNone
     * @covers \Ghostwriter\Option\Some::__construct
     * @covers \Ghostwriter\Option\Some::create
     */
    public function testIsNone(): void
    {
        self::assertFalse($this->some->isNone());
    }

    /**
     * @covers \Ghostwriter\Option\Some::__construct
     * @covers \Ghostwriter\Option\Some::isSome
     * @covers \Ghostwriter\Option\Some::__construct
     * @covers \Ghostwriter\Option\Some::create
     */
    public function testIsSome(): void
    {
        self::assertTrue($this->some->isSome());
    }

    /**
     * @covers \Ghostwriter\Option\Some::__construct
     * @covers \Ghostwriter\Option\Some::isSome
     * @covers \Ghostwriter\Option\Some::of
     * @covers \Ghostwriter\Option\Some::map
     * @covers \Ghostwriter\Option\Some::unwrap
     * @covers \Ghostwriter\Option\Some::__construct
     * @covers \Ghostwriter\Option\Some::create
     */
    public function testMap(): void
    {
        $option = $this->some->map(static fn (mixed $x): string => sprintf('%s%s', (string) $x, 'bar'));
        self::assertTrue($option->isSome());
        self::assertSame('foobar', $option->unwrap());
    }

    /**
     * @covers \Ghostwriter\Option\Some::__construct
     * @covers \Ghostwriter\Option\Some::mapOr
     * @covers \Ghostwriter\Option\Some::__construct
     * @covers \Ghostwriter\Option\Some::create
     */
    public function testMapOr(): void
    {
        self::assertSame(
            'foobar',
            $this->some->mapOr(static fn (mixed $x): string => sprintf('%s%s', (string) $x, 'bar'), 'baz')
        );
    }

    /**
     * @covers \Ghostwriter\Option\Some::__construct
     * @covers \Ghostwriter\Option\Some::mapOrElse
     * @covers \Ghostwriter\Option\Some::__construct
     * @covers \Ghostwriter\Option\Some::create
     */
    public function testMapOrElse(): void
    {
        $some = static fn (mixed $value): string => (string) $value;

        $none = static fn (): string => 'failed!';

        self::assertSame('foo', $this->some->mapOrElse($some, $none));
    }

    /**
     * @covers       \Ghostwriter\Option\Some::__construct
     * @covers       \Ghostwriter\Option\Some::create
     * @covers       \Ghostwriter\Option\Some::of
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
        $option = Some::of($value);

        if ($value instanceof OptionInterface) {
            self::assertSame($value, $option);
        }

        self::assertInstanceOf($expected, $option);
    }

    /**
     * @covers \Ghostwriter\Option\Some::__construct
     * @covers \Ghostwriter\Option\Some::or
     * @covers \Ghostwriter\Option\Some::__construct
     * @covers \Ghostwriter\Option\Some::create
     */
    public function testOr(): void
    {
        $some = Some::create('foo');
        self::assertSame($this->some, $this->some->or($some));
    }

    /**
     * @covers \Ghostwriter\Option\Some::__construct
     * @covers \Ghostwriter\Option\Some::orElse
     * @covers \Ghostwriter\Option\Some::__construct
     * @covers \Ghostwriter\Option\Some::create
     */
    public function testOrElse(): void
    {
        self::assertSame($this->some, $this->some->orElse(static function (): void {
            throw new RuntimeException('Should not be called!');
        }));
    }

    /**
     * @covers \Ghostwriter\Option\Some::__construct
     * @covers \Ghostwriter\Option\Some::unwrap
     * @covers \Ghostwriter\Option\Some::__construct
     * @covers \Ghostwriter\Option\Some::create
     */
    public function testUnwrap(): void
    {
        self::assertSame('foo', $this->some->unwrap());
    }

    /**
     * @covers \Ghostwriter\Option\Some::__construct
     * @covers \Ghostwriter\Option\Some::unwrapOr
     * @covers \Ghostwriter\Option\Some::__construct
     * @covers \Ghostwriter\Option\Some::create
     */
    public function testUnwrapOr(): void
    {
        self::assertSame('foo', $this->some->unwrapOr('fallback'));
    }

    /**
     * @covers \Ghostwriter\Option\Some::__construct
     * @covers \Ghostwriter\Option\Some::unwrapOrElse
     * @covers \Ghostwriter\Option\Some::__construct
     * @covers \Ghostwriter\Option\Some::create
     */
    public function testUnwrapOrElse(): void
    {
        self::assertSame('foo', $this->some->unwrapOrElse(static fn (): string => 'bar'));
    }
}
