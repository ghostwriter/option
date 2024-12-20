<?php

declare(strict_types=1);

namespace Tests\Unit\Exception;

use Ghostwriter\Option\Exception\OptionException;
use Ghostwriter\Option\None;
use Ghostwriter\Option\Some;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Throwable;

#[CoversClass(OptionException::class)]
#[UsesClass(None::class)]
#[UsesClass(Some::class)]
final class OptionExceptionTest extends TestCase
{
    /**
     * @throws Throwable
     */
    public function testSomeAndThen(): void
    {
        $this->expectException(OptionException::class);

        Some::new(42)->andThen(/** @return int */ static fn (int $x): int => $x);
    }
}
