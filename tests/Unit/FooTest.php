<?php

declare(strict_types=1);

namespace Ghostwriter\wip\Tests\Unit;

use Ghostwriter\wip\Foo;

/** @coversDefaultClass \Ghostwriter\wip\Foo */
class FooTest extends AbstractTestCase
{
    /** @covers ::test */
    public function test(): void
    {
        $this->assertTrue((new Foo())->test());
    }
}
