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
     * @return Traversable<array-key, array{0:class-string,1:mixed,2:mixed}>
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
        yield 'None' => [NoneInterface::class, [None::class, 'create']];
        yield 'null:null' => [NoneInterface::class, null, null];
        yield 'null:false' => [NoneInterface::class, null, false];
        yield 'true:true' => [NoneInterface::class, true, true];
        yield 'false:false' => [NoneInterface::class, false, false];
        yield 'null:true' => [NoneInterface::class, null, true];
        yield 'string:string' => [NoneInterface::class, 'string', 'string'];
    }

    /**
     * @covers       \Ghostwriter\Option\Some::__construct
     * @covers       \Ghostwriter\Option\Some::create
     * @covers       \Ghostwriter\Option\None::create
     *
     * @dataProvider ofDataProvider
     *
     * @param class-string $expected
     *
     * @throws ReflectionException
     */
    public function testOf(string $expected, mixed $value, mixed $noneValue = null): void
    {
        self::assertInstanceOf($expected, AbstractOption::of($value, $noneValue));
    }
}
