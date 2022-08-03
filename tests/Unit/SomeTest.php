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
     * @return Traversable<array-key, array>
     */
    public function invalidNoneValueDataProvider(): Traversable
    {
        yield 'value: null' =>[null];
    }

    /**
     * @covers \Ghostwriter\Option\AbstractOption::__construct
     * @covers \Ghostwriter\Option\AbstractOption::and
     * @covers \Ghostwriter\Option\Some::create
     * @covers \Ghostwriter\Option\Some::__construct
     * @covers \Ghostwriter\Option\Some::create
     */
    public function testAnd(): void
    {
        $foo = Some::create('foo');
        self::assertSame($foo, $this->some->and($foo));
    }

    /**
     * @covers \Ghostwriter\Option\AbstractOption::__construct
     * @covers \Ghostwriter\Option\AbstractOption::of
     * @covers \Ghostwriter\Option\AbstractOption::andThen
     * @covers \Ghostwriter\Option\AbstractOption::unwrap
     * @covers \Ghostwriter\Option\Some::__construct
     * @covers \Ghostwriter\Option\Some::create
     */
    public function testAndThen(): void
    {
        $option = $this->some->andThen(static fn (mixed $x): string => (string) $x);

        self::assertInstanceOf(SomeInterface::class, $option);
        self::assertSame('foo', $option->unwrap());
    }

    /**
     * @covers \Ghostwriter\Option\AbstractOption::__construct
     * @covers \Ghostwriter\Option\AbstractOption::contains
     * @covers \Ghostwriter\Option\Some::__construct
     * @covers \Ghostwriter\Option\Some::create
     */
    public function testContains(): void
    {
        self::assertTrue($this->some->contains('foo'));

        self::assertFalse($this->some->contains(true));
    }

    /**
     * @covers \Ghostwriter\Option\AbstractOption::__construct
     * @covers \Ghostwriter\Option\Some::__construct
     * @covers \Ghostwriter\Option\Some::create
     * @dataProvider invalidNoneValueDataProvider
     */
    public function testCreate(mixed $value): void
    {
        $this->expectException(NullPointerException::class);
        self::assertInstanceOf(OptionInterface::class, Some::create($value));
        self::assertSame($this->some, Some::create($value));
    }

    /**
     * @covers \Ghostwriter\Option\AbstractOption::__construct
     * @covers \Ghostwriter\Option\AbstractOption::expect
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
     * @covers \Ghostwriter\Option\AbstractOption::__construct
     * @covers \Ghostwriter\Option\AbstractOption::of
     * @covers \Ghostwriter\Option\AbstractOption::andThen
     * @covers \Ghostwriter\Option\AbstractOption::filter
     * @covers \Ghostwriter\Option\AbstractOption::isNone
     * @covers \Ghostwriter\Option\None::create
     * @covers \Ghostwriter\Option\Some::__construct
     * @covers \Ghostwriter\Option\Some::create
     */
    public function testFilter(): void
    {
        // returns the instance if its type is Some and the given function returns true.
        self::assertSame($this->some, $this->some->filter(static fn ($x) => 'foo' === $x));

        // returns an instance of None if called on an instance of Some and the given function returns false.
        self::assertTrue($this->some->filter(static fn ($x) => 'bar' === $x)->isNone());
    }

    /**
     * @covers \Ghostwriter\Option\AbstractOption::__construct
     * @covers \Ghostwriter\Option\AbstractOption::flatten
     * @covers \Ghostwriter\Option\AbstractOption::unwrap
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
     * @covers \Ghostwriter\Option\AbstractOption::__construct
     * @covers \Ghostwriter\Option\AbstractOption::getIterator
     * @covers \Ghostwriter\Option\Some::__construct
     * @covers \Ghostwriter\Option\Some::create
     */
    public function testGetIterator(): void
    {
        self::assertCount(1, $this->some);
    }

    /**
     * @covers \Ghostwriter\Option\AbstractOption::__construct
     * @covers \Ghostwriter\Option\AbstractOption::isNone
     * @covers \Ghostwriter\Option\Some::__construct
     * @covers \Ghostwriter\Option\Some::create
     */
    public function testIsNone(): void
    {
        self::assertFalse($this->some->isNone());
    }

    /**
     * @covers \Ghostwriter\Option\AbstractOption::__construct
     * @covers \Ghostwriter\Option\AbstractOption::isSome
     * @covers \Ghostwriter\Option\Some::__construct
     * @covers \Ghostwriter\Option\Some::create
     */
    public function testIsSome(): void
    {
        self::assertTrue($this->some->isSome());
    }

    /**
     * @covers \Ghostwriter\Option\AbstractOption::__construct
     * @covers \Ghostwriter\Option\AbstractOption::isSome
     * @covers \Ghostwriter\Option\AbstractOption::of
     * @covers \Ghostwriter\Option\AbstractOption::map
     * @covers \Ghostwriter\Option\AbstractOption::unwrap
     * @covers \Ghostwriter\Option\Some::__construct
     * @covers \Ghostwriter\Option\Some::create
     */
    public function testMap(): void
    {
        $option = $this->some->map(static fn (mixed $x) => sprintf('%s%s', (string) $x, 'bar'));
        self::assertTrue($option->isSome());
        self::assertSame('foobar', $option->unwrap());
    }

    /**
     * @covers \Ghostwriter\Option\AbstractOption::__construct
     * @covers \Ghostwriter\Option\AbstractOption::mapOr
     * @covers \Ghostwriter\Option\Some::__construct
     * @covers \Ghostwriter\Option\Some::create
     */
    public function testMapOr(): void
    {
        self::assertSame(
            'foobar',
            $this->some->mapOr(static fn (mixed $x) => sprintf('%s%s', (string) $x, 'bar'), 'baz')
        );
    }

    /**
     * @covers \Ghostwriter\Option\AbstractOption::__construct
     * @covers \Ghostwriter\Option\AbstractOption::mapOrElse
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
     * @covers \Ghostwriter\Option\AbstractOption::__construct
     * @covers \Ghostwriter\Option\AbstractOption::or
     * @covers \Ghostwriter\Option\Some::__construct
     * @covers \Ghostwriter\Option\Some::create
     */
    public function testOr(): void
    {
        $some = Some::create('foo');
        self::assertSame($this->some, $this->some->or($some));
    }

    /**
     * @covers \Ghostwriter\Option\AbstractOption::__construct
     * @covers \Ghostwriter\Option\AbstractOption::orElse
     * @covers \Ghostwriter\Option\Some::__construct
     * @covers \Ghostwriter\Option\Some::create
     */
    public function testOrElse(): void
    {
        self::assertSame($this->some, $this->some->orElse(static function () {
            throw new RuntimeException('Should not be called!');
        }));
    }

    /**
     * @covers \Ghostwriter\Option\AbstractOption::__construct
     * @covers \Ghostwriter\Option\AbstractOption::unwrap
     * @covers \Ghostwriter\Option\Some::__construct
     * @covers \Ghostwriter\Option\Some::create
     */
    public function testUnwrap(): void
    {
        self::assertSame('foo', $this->some->unwrap());
    }

    /**
     * @covers \Ghostwriter\Option\AbstractOption::__construct
     * @covers \Ghostwriter\Option\AbstractOption::unwrapOr
     * @covers \Ghostwriter\Option\Some::__construct
     * @covers \Ghostwriter\Option\Some::create
     */
    public function testUnwrapOr(): void
    {
        self::assertSame('foo', $this->some->unwrapOr('fallback'));
    }

    /**
     * @covers \Ghostwriter\Option\AbstractOption::__construct
     * @covers \Ghostwriter\Option\AbstractOption::unwrapOrElse
     * @covers \Ghostwriter\Option\Some::__construct
     * @covers \Ghostwriter\Option\Some::create
     */
    public function testUnwrapOrElse(): void
    {
        self::assertSame('foo', $this->some->unwrapOrElse(static fn () => 'bar'));
    }
}
