<?php

declare(strict_types=1);

namespace Tests\Unit;

use Ghostwriter\Option\Interface\NoneInterface;
use Ghostwriter\Option\Interface\SomeInterface;
use Ghostwriter\Option\None;
use Ghostwriter\Option\Some;
use Override;
use PHPUnit\Framework\TestCase;
use Throwable;

abstract class AbstractTestCase extends TestCase
{
    public const string BLACK_LIVES_MATTER = '#BlackLivesMatter';

    public readonly NoneInterface $none;

    public readonly SomeInterface $some;

    /** @throws Throwable */
    #[Override]
    final protected function setUp(): void
    {
        parent::setUp();

        $this->none = None::new();

        $this->some = Some::new(self::BLACK_LIVES_MATTER);
    }
}
