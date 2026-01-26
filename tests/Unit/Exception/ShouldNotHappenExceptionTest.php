<?php

declare(strict_types=1);

namespace Tests\Unit\Exception;

use Ghostwriter\Option\Exception\ShouldNotHappenException;
use Ghostwriter\Option\Interface\OptionInterface;
use Ghostwriter\Option\None;
use Ghostwriter\Option\Some;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\Unit\AbstractTestCase;
use Throwable;

use function sprintf;

#[CoversClass(ShouldNotHappenException::class)]
#[CoversClass(None::class)]
#[CoversClass(Some::class)]
final class ShouldNotHappenExceptionTest extends AbstractTestCase
{
    /**
     * @throws Throwable
     */
    public function testReturningNonBoolFromSomeFilterThrowsShouldNotHappenException(): void
    {
        $this->expectException(ShouldNotHappenException::class);

        $this->expectExceptionMessage('Callable passed to filter() must return a boolean, string given.');

        $this->some->filter(static fn (): string => self::BLACK_LIVES_MATTER);
    }

    /**
     * @throws Throwable
     */
    public function testReturningNonOptionFromSomeAndThenThrowsShouldNotHappenException(): void
    {
        $this->expectException(ShouldNotHappenException::class);

        $this->expectExceptionMessage(sprintf(
            'Callable passed to andThen() must return an instance of %s.',
            OptionInterface::class,
        ));

        $this->some->andThen(static fn (): string => self::BLACK_LIVES_MATTER);
    }
}
