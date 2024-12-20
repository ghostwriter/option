<?php

declare(strict_types=1);

namespace Tests\Unit\Exception;

use Ghostwriter\Option\Exception\NullPointerException;
use Ghostwriter\Option\None;
use Ghostwriter\Option\Some;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Throwable;

#[CoversClass(NullPointerException::class)]
#[UsesClass(None::class)]
#[UsesClass(Some::class)]
final class NullPointerExceptionTest extends TestCase
{
    /**
     * @throws Throwable
     */
    public function testNoneUnwrap(): void
    {
        $this->expectException(NullPointerException::class);

        None::new()->unwrap();
    }

    /**
     * @throws Throwable
     */
    public function testSomeNew(): void
    {
        $this->expectException(NullPointerException::class);

        Some::new(null);
    }
}
