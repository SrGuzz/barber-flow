<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function admin(User $user) : bool
    {
        return $user->is_admin();
    }
}
