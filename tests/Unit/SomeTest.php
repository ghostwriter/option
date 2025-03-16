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
use Tests\Unit\DataProvider\SomeDataProvider;
use Throwable;

use function sprintf;

#[CoversClass(None::class)]
#[CoversClass(Option::class)]
#[CoversClass(Some::class)]
final class SomeTest extends AbstractTestCase
{
    /** @throws Throwable */
    public function testAnd(): void
    {
        self::assertSame($this->some, $this->some->and($this->some));
    }

    /** @throws Throwable */
    public function testAndThen(): void
    {
        self::assertSame(
            self::BLACK_LIVES_MATTER,
            $this->some->andThen(static fn (mixed $x): mixed => Some::new($x))->get()
        );

        self::assertSame(
            self::BLACK_LIVES_MATTER,
            $this->some->andThen(static fn (mixed $x): SomeInterface => Some::new($x))->get()
        );
    }

    /** @throws Throwable */
    public function testExpect(): void
    {
        self::assertSame(self::BLACK_LIVES_MATTER, $this->some->expect(new RuntimeException(__FUNCTION__)));
    }

    /** @throws Throwable */
    public function testFilter(): void
    {
        // returns the instance if its type is Some and the given function returns true.
        self::assertSame(
            $this->some,
            $this->some->filter(static fn ($x): bool => self::BLACK_LIVES_MATTER === $x),
        );

        // returns an instance of None if called on an instance of Some and the given function returns false.
        self::assertInstanceOf(NoneInterface::class, $this->some->filter(static fn (string $x): bool => 'b' === $x[0]));
    }

    /** @throws Throwable */
    public function testGet(): void
    {
        self::assertSame(self::BLACK_LIVES_MATTER, $this->some->get());
    }

    /** @throws Throwable */
    public function testGetOr(): void
    {
        self::assertSame(self::BLACK_LIVES_MATTER, $this->some->getOr('fallback'));
    }

    /** @throws Throwable */
    public function testGetOrElse(): void
    {
        self::assertSame(self::BLACK_LIVES_MATTER, $this->some->getOrElse(static fn (): string => 'bar'));
    }

    /** @throws Throwable */
    public function testIs(): void
    {
        self::assertTrue($this->some->is(self::BLACK_LIVES_MATTER));

        self::assertFalse($this->some->is(true));
    }

    /** @throws Throwable */
    public function testIsNone(): void
    {
        self::assertFalse($this->some->isNone());
    }

    /** @throws Throwable */
    public function testIsSome(): void
    {
        self::assertTrue($this->some->isSome());
    }

    /** @throws Throwable */
    public function testMap(): void
    {
        $option = $this->some->map(static fn (mixed $x): string => sprintf('%s%s', $x, '@'));

        self::assertInstanceOf(SomeInterface::class, $option);

        self::assertSame(self::BLACK_LIVES_MATTER . '@', $option->get());

        $some = Some::new('foo');

        $none = $some->map(
            /**
             * @param non-empty-string $x
             *
             * @return non-empty-string
             */
            static fn (mixed $x): string => sprintf('%s%s', $x, 'bar')
        );

        self::assertTrue($none->isSome());
        self::assertSame('foobar', $none->get());
    }

    /** @throws Throwable */
    public function testMapOr(): void
    {
        self::assertSame(self::BLACK_LIVES_MATTER . '@', $this->some->mapOr(
            static fn (mixed $x): string => sprintf('%s%s', $x, '@'),
            'baz'
        ));
    }

    /** @throws Throwable */
    public function testMapOrElse(): void
    {
        $someFn = static fn (mixed $value): string => (string) $value;

        $noneFn = static fn (): string => 'failed';

        self::assertSame(self::BLACK_LIVES_MATTER, $this->some->mapOrElse($someFn, $noneFn));
    }

    /**
     * @template TValue
     *
     * @param class-string $expected
     * @param TValue       $value
     *
     * @throws Throwable
     */
    #[DataProviderExternal(SomeDataProvider::class, 'provide')]
    public function testNew(string $expected, mixed $value): void
    {
        $some = Some::new($value);

        self::assertInstanceOf($expected, $some);

        if ($value instanceof OptionInterface) {
            self::assertSame($value, $some);
        }

        self::assertInstanceOf(SomeInterface::class, $some);
    }

    /** @throws Throwable */
    public function testNone(): void
    {
        self::assertSame(None::new(), Option::new(null));
    }

    /** @throws Throwable */
    public function testOr(): void
    {
        self::assertSame($this->some, $this->some->or($this->some));
    }

    /** @throws Throwable */
    public function testOrElse(): void
    {
        self::assertSame($this->some, $this->some->orElse(static function (): never {
            throw new RuntimeException('Should not be called!');
        }));
    }
}
