<?php

declare(strict_types=1);

namespace Ghostwriter\Option\Tests\Unit;

use Ghostwriter\Option\Contract\NoneInterface;
use Ghostwriter\Option\Exception\InvalidReturnTypeException;
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
    private NoneInterface $none;

    protected function setUp(): void
    {
        $this->none = None::create();
    }

    /**
     * @covers \Ghostwriter\Option\AbstractOption::and
     * @covers \Ghostwriter\Option\None::create
     * @covers \Ghostwriter\Option\Some::__construct
     * @covers \Ghostwriter\Option\Some::create
     */
    public function testAnd(): void
    {
        $some = Some::create('foobar');
        $option = $this->none->and($some);

        self::assertSame($this->none, $option);
        self::assertInstanceOf(NoneInterface::class, $option);
    }

    /**
     * @covers \Ghostwriter\Option\AbstractOption::andThen
     * @covers \Ghostwriter\Option\None::create
     */
    public function testAndThen(): void
    {
        self::assertInstanceOf(NoneInterface::class, $this->none->andThen(static fn ($x) => Some::create($x)));
    }

    /**
     * @covers \Ghostwriter\Option\AbstractOption::contains
     * @covers \Ghostwriter\Option\None::create
     */
    public function testContains(): void
    {
        self::assertFalse($this->none->contains(null));
    }

    /**
     * @covers \Ghostwriter\Option\None::create
     */
    public function testCreate(): void
    {
        self::assertSame($this->none, None::create());
    }

    /**
     * @covers \Ghostwriter\Option\AbstractOption::expect
     * @covers \Ghostwriter\Option\None::create
     *
     * @throws Throwable
     */
    public function testExpect(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Expect');

        $this->none->expect(new RuntimeException('Expect'));
    }

    /**
     * @covers \Ghostwriter\Option\AbstractOption::andThen
     * @covers \Ghostwriter\Option\AbstractOption::filter
     * @covers \Ghostwriter\Option\None::create
     */
    public function testFilter(): void
    {
        $option = $this->none->filter(static fn ($x) => null === $x);
        self::assertInstanceOf(NoneInterface::class, $option);
    }

    /**
     * @covers \Ghostwriter\Option\AbstractOption::flatten
     * @covers \Ghostwriter\Option\None::create
     */
    public function testFlatten(): void
    {
        $option = $this->none->flatten();
        self::assertSame($this->none, $option);
        self::assertInstanceOf(NoneInterface::class, $option);
    }

    /**
     * @covers \Ghostwriter\Option\AbstractOption::getIterator
     * @covers \Ghostwriter\Option\None::create
     */
    public function testGetIterator(): void
    {
        self::assertCount(0, $this->none);
    }

    /**
     * @covers \Ghostwriter\Option\AbstractOption::isNone
     * @covers \Ghostwriter\Option\None::create
     */
    public function testIsNone(): void
    {
        self::assertTrue($this->none->isNone());
    }

    /**
     * @covers \Ghostwriter\Option\AbstractOption::isSome
     * @covers \Ghostwriter\Option\None::create
     */
    public function testIsSome(): void
    {
        self::assertFalse($this->none->isSome());
    }

    /**
     * @covers \Ghostwriter\Option\AbstractOption::map
     * @covers \Ghostwriter\Option\None::create
     */
    public function testMap(): void
    {
        self::assertInstanceOf(NoneInterface::class, $this->none->map(static function () {
            self::fail();
        }));
    }

    /**
     * @covers \Ghostwriter\Option\AbstractOption::mapOr
     * @covers \Ghostwriter\Option\None::create
     */
    public function testMapOr(): void
    {
        self::assertSame('baz', $this->none->mapOr('baz', static function () {
            self::fail();
        }));
    }

    /**
     * @covers \Ghostwriter\Option\AbstractOption::mapOrElse
     * @covers \Ghostwriter\Option\None::create
     */
    public function testMapOrElse(): void
    {
        $some = static fn (string $value): string => $value . 'bar';

        $none = static fn (): string => 'baz';

        self::assertSame('baz', $this->none->mapOrElse($none, $some));
    }

    /**
     * @covers \Ghostwriter\Option\AbstractOption::or
     * @covers \Ghostwriter\Option\None::create
     * @covers \Ghostwriter\Option\Some::__construct
     * @covers \Ghostwriter\Option\Some::create
     */
    public function testOr(): void
    {
        $option = Some::create('foobar');
        self::assertSame($option, $this->none->or($option));
    }

    /**
     * @covers \Ghostwriter\Option\AbstractOption::orElse
     * @covers \Ghostwriter\Option\None::create
     * @covers \Ghostwriter\Option\Some::__construct
     * @covers \Ghostwriter\Option\Some::create
     */
    public function testOrElse(): void
    {
        $some = Some::create('foo');
        self::assertSame($some, $this->none->orElse(static fn () => $some));

        $this->expectException(InvalidReturnTypeException::class);
        self::assertSame($this->none, $this->none->orElse(static fn () => 0));
    }

    /**
     * @covers \Ghostwriter\Option\AbstractOption::unwrap
     * @covers \Ghostwriter\Option\None::create
     */
    public function testUnwrap(): void
    {
        $this->expectException(NullPointerException::class);
        $this->none->unwrap();
    }

    /**
     * @covers \Ghostwriter\Option\AbstractOption::unwrapOr
     * @covers \Ghostwriter\Option\None::create
     */
    public function testUnwrapOr(): void
    {
        self::assertSame('UnwrapOr', $this->none->unwrapOr('UnwrapOr'));
    }

    /**
     * @covers \Ghostwriter\Option\AbstractOption::unwrapOrElse
     * @covers \Ghostwriter\Option\None::create
     */
    public function testUnwrapOrElse(): void
    {
        $function = static fn (): string => 'UnwrapOrElse';

        self::assertSame('UnwrapOrElse', $this->none->unwrapOrElse($function));
    }

    /**
     * @covers \Ghostwriter\Option\AbstractOption::isNone
     * @covers \Ghostwriter\Option\AbstractOption::unwrap
     * @covers \Ghostwriter\Option\AbstractOption::xor
     * @covers \Ghostwriter\Option\None::create
     * @covers \Ghostwriter\Option\Some::__construct
     * @covers \Ghostwriter\Option\Some::create
     */
    public function testXor(): void
    {
        // returns the given option if its type is Some and the current instance is None
        $some = Some::create('foo');
        self::assertSame($some, $this->none->xor($some));

        // returns an instance of None if the current instance is None and the given option is None
        $none = None::create();
        self::assertSame($none, $this->none->xor($none));
    }
}
