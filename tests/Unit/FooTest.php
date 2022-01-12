<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use App\Foo;
use App\Tests\AbstractTestCase;

/** @coversDefaultClass \App\Foo */
class FooTest extends AbstractTestCase
{
    /** @covers ::test */
    public function test(): void
    {
        $this->assertTrue((new Foo())->test());
    }
}
