<?php

declare(strict_types=1);

namespace Tests\Unit;

use Ghostwriter\Option\Interface\NoneInterface;
use Ghostwriter\Option\Interface\OptionInterface;
use Ghostwriter\Option\Interface\SomeInterface;
use Ghostwriter\Option\None;
use Ghostwriter\Option\Option;
use Ghostwriter\Option\Some;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProviderExternal;
use RuntimeException;
use Tests\Unit\DataProvider\NoneDataProvider;
use Throwable;

use function get_debug_type;
use function sprintf;

#[CoversClass(None::class)]
#[CoversClass(Option::class)]
#[CoversClass(Some::class)]
final class NoneTest extends AbstractTestCase
{
    /** @throws Throwable */
    public function testAnd(): void
    {
        $option = $this->none->and($this->some);

        self::assertSame($this->none, $option);
        self::assertInstanceOf(NoneInterface::class, $option);
    }

    /** @throws Throwable */
    public function testAndThen(): void
    {
        self::assertFalse($this->none->andThen(static fn (): SomeInterface => Some::new(true))->getOr(false));
    }

    /** @throws Throwable */
    public function testContains(): void
    {
        self::assertTrue($this->none->is(null));
    }

    /** @throws Throwable */
    public function testCreate(): void
    {
        self::assertSame($this->none, None::new());
    }

    /** @throws Throwable */
    public function testExpect(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(__FUNCTION__);

        None::new()->expect(new RuntimeException(__FUNCTION__));
    }

    /** @throws Throwable */
    public function testFilter(): void
    {
        self::assertInstanceOf(
            NoneInterface::class,
            $this->none->filter(static fn (mixed $x): bool => null === $x),
        );
    }

    /** @throws Throwable */
    public function testGetOr(): void
    {
        self::assertSame('GetOr', $this->none->getOr('GetOr'));
    }

    /** @throws Throwable */
    public function testGetOrElse(): void
    {
        self::assertSame('GetOrElse', $this->none->getOrElse(static fn (): string => 'GetOrElse'));
    }

    /** @throws Throwable */
    public function testIsNone(): void
    {
        self::assertTrue($this->none->isNone());
    }

    /** @throws Throwable */
    public function testIsSome(): void
    {
        self::assertFalse($this->none->isSome());
    }

    /** @throws Throwable */
    public function testMap(): void
    {
        self::assertInstanceOf(NoneInterface::class, $this->none->map(static fn (): int => 0));
    }

    /** @throws Throwable */
    public function testMapOr(): void
    {
        self::assertSame('baz', $this->none->mapOr(static fn (): int => 0, 'baz'));
    }

    /**
     * @template TMixed
     *
     * @throws Throwable
     */
    public function testMapOrElse(): void
    {
        $someFn = static fn (mixed $value): string => sprintf('%sbar', get_debug_type($value));

        $noneFn = static fn (): string => 'baz';

        self::assertSame('baz', $this->none->mapOrElse($someFn, $noneFn));
    }

    /**
     * @template TValue
     *
     * @param class-string $expected
     * @param TValue       $value
     *
     * @throws Throwable
     */
    #[DataProviderExternal(NoneDataProvider::class, 'provide')]
    public function testNew(string $expected, mixed $value): void
    {
        $none = None::new();

        self::assertInstanceOf($expected, $none);

        if ($value instanceof OptionInterface) {
            self::assertSame($value, $none);
        }

        self::assertInstanceOf(NoneInterface::class, $none);
    }

    /** @throws Throwable */
    public function testOr(): void
    {
        self::assertSame($this->some, $this->none->or($this->some));
    }

    /** @throws Throwable */
    public function testOrElse(): void
    {
        $none = $this->none;
        $some = $this->some;

        self::assertSame($none, $this->none->orElse(static fn (): NoneInterface => $none));

        self::assertSame($some, $this->none->orElse(static fn (): SomeInterface => $some));
    }
}
