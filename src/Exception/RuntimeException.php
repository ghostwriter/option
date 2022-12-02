<?php

declare(strict_types=1);

namespace Ghostwriter\Option\Exception;

use Ghostwriter\Option\Contract\Exception\OptionExceptionInterface;
use RuntimeException as PHPRuntimeException;

final class RuntimeException extends PHPRuntimeException implements OptionExceptionInterface
{
}
