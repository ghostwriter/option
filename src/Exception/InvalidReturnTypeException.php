<?php

declare(strict_types=1);

namespace Ghostwriter\Option\Exception;

use DomainException as PHPDomainException;
use Ghostwriter\Option\Contract\Exception\OptionExceptionInterface;

final class InvalidReturnTypeException extends PHPDomainException implements OptionExceptionInterface
{
}
