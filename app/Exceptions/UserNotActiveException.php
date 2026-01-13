<?php

namespace App\Exceptions;

class UserNotActiveException extends BusinessException
{
    protected int $statusCode = 403;
    protected $message = 'User is not active';
}
