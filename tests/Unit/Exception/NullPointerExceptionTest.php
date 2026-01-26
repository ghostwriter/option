<?php

declare(strict_types=1);

namespace Tests\Unit\Exception;

use Ghostwriter\Option\Exception\NullPointerException;
use Ghostwriter\Option\None;
use Ghostwriter\Option\Some;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\Unit\AbstractTestCase;
use Throwable;

#[CoversClass(NullPointerException::class)]
#[CoversClass(None::class)]
#[CoversClass(Some::class)]
final class NullPointerExceptionTest extends AbstractTestCase
{
    /** @throws Throwable */
    public function testCallingGetOnNoneThrowsNullPointerException(): void
    {
        $this->expectException(NullPointerException::class);

        $this->none->get();
    }

    /** @throws Throwable */
    public function testConstructingSomeWithNoneThrowsNullPointerException(): void
    {
        $this->expectException(NullPointerException::class);

        Some::new($this->none);
    }

    /** @throws Throwable */
    public function testConstructingSomeWithNullThrowsNullPointerException(): void
    {
        $this->expectException(NullPointerException::class);

        Some::new(null);
    }
}
