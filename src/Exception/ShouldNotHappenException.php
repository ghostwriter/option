<?php

declare(strict_types=1);

namespace Ghostwriter\Option\Exception;

use Ghostwriter\Option\Interface\ExceptionInterface;
use LogicException;

final class ShouldNotHappenException extends LogicException implements ExceptionInterface {}
