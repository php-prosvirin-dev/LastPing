<?php

namespace App\Actions;

use App\Models\User;

class CheckInUser
{
    public function execute(User $user): User
    {
        $user->forceFill([
            'last_check_in_at' => now(),
        ])->save();

        return $user->fresh();
    }
}
