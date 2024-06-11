<?php

declare(strict_types=1);

namespace Ghostwriter\Option\Exception;

use InvalidArgumentException;

final class NullPointerException extends InvalidArgumentException implements OptionExceptionInterface
{
}
