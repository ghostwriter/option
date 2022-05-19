<?php

declare(strict_types=1);

namespace Ghostwriter\Option\Exception;

use BadMethodCallException as PHPBadMethodCallException;
use Ghostwriter\Option\Contract\Exception\OptionExceptionInterface;

final class NullPointerException extends PHPBadMethodCallException implements OptionExceptionInterface
{
}
