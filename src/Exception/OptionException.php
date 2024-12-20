<?php

declare(strict_types=1);

namespace Ghostwriter\Option\Exception;

use Ghostwriter\Option\Interface\ExceptionInterface;
use InvalidArgumentException;

final class OptionException extends InvalidArgumentException implements ExceptionInterface {}
