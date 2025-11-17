<?php

declare(strict_types=1);

namespace App\Query\Exceptions;

use App\Exceptions\TechnicalException;
use App\Query\Contracts\QueryServiceException;

class ServiceResolutionException extends TechnicalException implements QueryServiceException
{
}
