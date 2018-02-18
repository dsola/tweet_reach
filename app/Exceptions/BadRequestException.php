<?php

namespace App\Exceptions;

use Exceptions\DomainException;

class BadRequestException extends \RuntimeException implements DomainException
{
}