<?php

declare(strict_types=1);

namespace Ghostwriter\Option\Tests\Unit;

use Ghostwriter\Option\Contract\NoneInterface;
use Ghostwriter\Option\Contract\OptionInterface;
use Ghostwriter\Option\Contract\SomeInterface;
use Ghostwriter\Option\Exception\NullPointerException;
use Ghostwriter\Option\None;
use Ghostwriter\Option\Some;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Throwable;
use Traversable;

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
     * @var int
     */
    private const CALLED = 0;

    private NoneInterface $none;

    protected function setUp(): void
    {
        $this->none = None::create();
    }

    /**
     * @return Traversable<array-key, array{0:class-string,1:mixed}>
     */
    public function ofDataProvider(): Traversable
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
        $option = $this->none->and($some);

        self::assertSame($this->none, $option);
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
        $option = $this->none->andThen(static fn (): SomeInterface => Some::create(1+self::CALLED));
        self::assertSame(0, $option->unwrapOr(self::CALLED));
    }

    /**
     * @covers \Ghostwriter\Option\None::__construct
     * @covers \Ghostwriter\Option\None::contains
     * @covers \Ghostwriter\Option\None::__construct
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
     * @covers \Ghostwriter\Option\None::__construct
     * @covers \Ghostwriter\Option\None::expect
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
     * @covers \Ghostwriter\Option\None::__construct
     * @covers \Ghostwriter\Option\None::map
     * @covers \Ghostwriter\Option\None::filter
     * @covers \Ghostwriter\Option\None::__construct
     * @covers \Ghostwriter\Option\None::create
     */
    public function testFilter(): void
    {
        $option = $this->none->filter(static fn ($x): bool => null === $x);
        self::assertInstanceOf(NoneInterface::class, $option);
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
        $option = $this->none->flatten();
        self::assertSame($this->none, $option);
        self::assertInstanceOf(NoneInterface::class, $option);
    }

    /**
     * @covers \Ghostwriter\Option\None::__construct
     * @covers \Ghostwriter\Option\None::getIterator
     * @covers \Ghostwriter\Option\None::create
     */
    public function testGetIterator(): void
    {
        self::assertCount(0, $this->none);
    }

    /**
     * @covers \Ghostwriter\Option\None::__construct
     * @covers \Ghostwriter\Option\None::isNone
     * @covers \Ghostwriter\Option\None::create
     */
    public function testIsNone(): void
    {
        self::assertTrue($this->none->isNone());
    }

    /**
     * @covers \Ghostwriter\Option\None::__construct
     * @covers \Ghostwriter\Option\None::isSome
     * @covers \Ghostwriter\Option\None::create
     */
    public function testIsSome(): void
    {
        self::assertFalse($this->none->isSome());
    }

    /**
     * @covers \Ghostwriter\Option\None::__construct
     * @covers \Ghostwriter\Option\None::map
     * @covers \Ghostwriter\Option\None::create
     */
    public function testMap(): void
    {
        self::assertInstanceOf(NoneInterface::class, $this->none->map(static fn (): int => 0));
    }

    /**
     * @covers \Ghostwriter\Option\None::__construct
     * @covers \Ghostwriter\Option\None::mapOr
     * @covers \Ghostwriter\Option\None::create
     */
    public function testMapOr(): void
    {
        self::assertSame('baz', $this->none->mapOr(static fn (): int => 0, 'baz'));
    }

    /**
     * @covers \Ghostwriter\Option\None::__construct
     * @covers \Ghostwriter\Option\None::mapOrElse
     * @covers \Ghostwriter\Option\None::__construct
     * @covers \Ghostwriter\Option\None::create
     */
    public function testMapOrElse(): void
    {
        $some = static fn (mixed $value): string => sprintf('%sbar', (string) $value);

        $none = static fn (): string => 'baz';

        self::assertSame('baz', $this->none->mapOrElse($some, $none));
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
        $some = Some::create('foobar');
        self::assertSame($some, $this->none->or($some));
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
        self::assertSame($none, $this->none);
        self::assertSame($none, $this->none->orElse(static fn (): NoneInterface => $none));
        self::assertSame($some, $this->none->orElse(static fn (): SomeInterface => $some));
    }

    /**
     * @covers \Ghostwriter\Option\None::__construct
     * @covers \Ghostwriter\Option\None::unwrap
     * @covers \Ghostwriter\Option\None::__construct
     * @covers \Ghostwriter\Option\None::create
     */
    public function testUnwrap(): void
    {
        $this->expectException(NullPointerException::class);
        $this->none->unwrap();
    }

    /**
     * @covers \Ghostwriter\Option\None::__construct
     * @covers \Ghostwriter\Option\None::unwrapOr
     * @covers \Ghostwriter\Option\None::__construct
     * @covers \Ghostwriter\Option\None::create
     */
    public function testUnwrapOr(): void
    {
        self::assertSame('UnwrapOr', $this->none->unwrapOr('UnwrapOr'));
    }

    /**
     * @covers \Ghostwriter\Option\None::__construct
     * @covers \Ghostwriter\Option\None::unwrapOrElse
     * @covers \Ghostwriter\Option\None::__construct
     * @covers \Ghostwriter\Option\None::create
     */
    public function testUnwrapOrElse(): void
    {
        $function = static fn (): string => 'UnwrapOrElse';

        self::assertSame('UnwrapOrElse', $this->none->unwrapOrElse($function));
    }
}
