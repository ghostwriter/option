<?php

declare(strict_types=1);

namespace Tests\Unit;

use Generator;
use Ghostwriter\Option\Exception\NullPointerException;
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
use Throwable;

use function get_debug_type;
use function iterator_to_array;
use function sprintf;

#[CoversClass(None::class)]
#[CoversClass(Some::class)]
#[Small]
final class NoneTest extends TestCase
{
    /**
     * @throws Throwable
     */
    public function testAnd(): void
    {
        $some = Some::new('foobar');
        $none = None::new();
        $option = $none->and($some);

        self::assertSame($none, $option);
        self::assertInstanceOf(NoneInterface::class, $option);
    }

    /**
     * @throws Throwable
     */
    public function testAndThen(): void
    {
        $option = None::new()->andThen(
            /** @return SomeInterface<true> */
            static fn (): SomeInterface => Some::new(true)
        );

        self::assertFalse($option->getOr(false));
    }

    /**
     * @throws Throwable
     */
    public function testContains(): void
    {
        $none = None::new();
        self::assertFalse($none->contains(null));
    }

    /**
     * @throws Throwable
     */
    public function testCreate(): void
    {
        $none = None::new();
        self::assertSame($none, None::new());
    }

    /**
     * @throws Throwable
     */
    public function testExpect(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(__FUNCTION__);

        None::new()->expect(new RuntimeException(__FUNCTION__));
    }

    /**
     * @throws Throwable
     */
    public function testFilter(): void
    {
        self::assertInstanceOf(NoneInterface::class, None::new()->filter(static fn (mixed $x): bool => null === $x));
    }

    /**
     * @throws Throwable
     */
    public function testFlatten(): void
    {
        $none = None::new();
        $option = $none->flatten();
        self::assertSame($none, $option);
        self::assertInstanceOf(NoneInterface::class, $option);
    }

    /**
     * @throws Throwable
     */
    public function testGetIterator(): void
    {
        $none = None::new();
        self::assertCount(0, iterator_to_array($none));
    }

    /**
     * @throws Throwable
     */
    public function testIsNone(): void
    {
        $none = None::new();
        self::assertTrue($none->isNone());
    }

    /**
     * @throws Throwable
     */
    public function testIsSome(): void
    {
        $none = None::new();
        self::assertFalse($none->isSome());
    }

    /**
     * @throws Throwable
     */
    public function testMap(): void
    {
        $none = None::new();
        self::assertInstanceOf(NoneInterface::class, $none->map(/** @return 0 */ static fn (): int => 0));
    }

    /**
     * @throws Throwable
     */
    public function testMapOr(): void
    {
        $none = None::new();
        self::assertSame('baz', $none->mapOr(/** @return 0 */ static fn (): int => 0, 'baz'));
    }

    /**
     * @template TMixed
     *
     * @throws Throwable
     */
    public function testMapOrElse(): void
    {
        $none = None::new();

        /**
         * @var Closure(TMixed):non-empty-string $someFn
         */
        $someFn = static fn (mixed $value): mixed => sprintf('%sbar', get_debug_type($value));

        $noneFn = /** @return 'baz' */ static fn (): string => 'baz';

        self::assertSame('baz', $none->mapOrElse($someFn, $noneFn));
    }

    /**
     *
     * @template TValue
     *
     * @param class-string $expected
     * @param TValue       $value
     *
     * @throws Throwable
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

    /**
     * @throws Throwable
     */
    public function testOr(): void
    {
        $none = None::new();
        $some = Some::new('foobar');
        self::assertSame($some, $none->or($some));
    }

    /**
     * @throws Throwable
     */
    public function testOrElse(): void
    {
        $none = None::new();

        $some = Some::new('foo');

        self::assertSame($none, $none->orElse(static fn (): NoneInterface => $none));

        self::assertSame($some, $none->orElse(
            /**
             * @return SomeInterface<'foo'>
             */
            static fn (): SomeInterface => $some
        ));
    }

    /**
     * @throws Throwable
     */
    public function testUnwrap(): void
    {
        $none = None::new();
        $this->expectException(NullPointerException::class);
        $none->get();
    }

    /**
     * @throws Throwable
     */
    public function testUnwrapOr(): void
    {
        $none = None::new();

        self::assertSame('UnwrapOr', $none->getOr('UnwrapOr'));
    }

    /**
     * @throws Throwable
     */
    public function testUnwrapOrElse(): void
    {
        $none = None::new();

        $function = /** @return 'UnwrapOrElse'  */ static fn (): string => 'UnwrapOrElse';

        self::assertSame('UnwrapOrElse', $none->getOrElse($function));
    }

    /**
     * @return Generator<'None::class'|'null',array{0:class-string<NoneInterface>,1:null|NoneInterface}>
     */
    public static function ofDataProvider(): Generator
    {
        yield 'null' => [NoneInterface::class, null];
        yield 'None::class' => [NoneInterface::class, None::new()];
    }
}
