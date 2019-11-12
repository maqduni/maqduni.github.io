<?php

namespace App\Policies;

use App\User;

class UserPolicy extends Policy
{
    public function logins(User $authenticated)
    {
        //
    }
}
