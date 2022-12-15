<?php

declare(strict_types=1);

namespace Ghostwriter\Option\Exception;

use Ghostwriter\Option\Contract\Exception\OptionExceptionInterface;
use RuntimeException;

final class OptionException extends RuntimeException implements OptionExceptionInterface
{
}
