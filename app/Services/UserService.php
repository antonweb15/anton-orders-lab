<?php

namespace App\Services;

use App\Exceptions\UserNotActiveException;

class UserService
{
    public function checkUser(bool $active): void
    {
        if (! $active) {
            throw new UserNotActiveException();
        }
    }
}
