<?php

namespace App\Client;

use Exceptions\DomainException;

class TwitterClientErrorException extends \RuntimeException implements DomainException
{
}