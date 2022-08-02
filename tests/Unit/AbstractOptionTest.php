<?php

declare(strict_types=1);

namespace Ghostwriter\Option\Tests\Unit;

use Ghostwriter\Option\AbstractOption;
use Ghostwriter\Option\Contract\NoneInterface;
use Ghostwriter\Option\Contract\SomeInterface;
use Ghostwriter\Option\None;
use Ghostwriter\Option\Some;
use PHPUnit\Framework\TestCase;
use ReflectionException;
use stdClass;
use Traversable;

/**
 * @internal
 *
 * @small
 * @covers \Ghostwriter\Option\AbstractOption
 */
final class AbstractOptionTest extends TestCase
{
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
            static fn () => 'Testing!',
        ];
        yield 'int' => [SomeInterface::class, 42];
        yield 'float' => [SomeInterface::class, 13.37];
        yield 'object' => [SomeInterface::class, new stdClass()];
        yield 'array' => [SomeInterface::class, []];
        yield 'Some::class' => [SomeInterface::class, Some::create(1337)];
        yield 'null' => [NoneInterface::class, null];
        yield 'Some::class' => [NoneInterface::class, None::create()];
    }

    /**
     * @covers       \Ghostwriter\Option\Some::__construct
     * @covers       \Ghostwriter\Option\Some::create
     * @covers       \Ghostwriter\Option\None::create
     *
     * @dataProvider ofDataProvider
     *
     * @param class-string $expected
     */
    public function testOf(string $expected, mixed $value): void
    {
        self::assertInstanceOf($expected, AbstractOption::of($value));
    }
}
