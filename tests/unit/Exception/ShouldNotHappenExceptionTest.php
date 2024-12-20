<?php

declare(strict_types=1);

namespace Tests\Unit\Exception;

use Ghostwriter\Option\Exception\ShouldNotHappenException;
use Ghostwriter\Option\None;
use Ghostwriter\Option\Some;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Throwable;

#[CoversClass(ShouldNotHappenException::class)]
#[UsesClass(None::class)]
#[UsesClass(Some::class)]
final class ShouldNotHappenExceptionTest extends TestCase
{
    /**
     * @throws Throwable
     */
    public function testExample(): void
    {
        $this->expectException(ShouldNotHappenException::class);

        Some::new(None::new());
    }
}
