<?php

declare(strict_types=1);

namespace Ghostwriter\Option\Contract;

use IteratorAggregate;

/**
 * @implements IteratorAggregate<TValue>
 * @template TValue
 */
interface OptionInterface extends IteratorAggregate
{
}
