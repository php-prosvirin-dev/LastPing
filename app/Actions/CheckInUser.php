<?php

namespace App\Actions;

use App\Models\CheckInLog;
use App\Models\User;

class CheckInUser
{
    public function execute(User $user): User
    {
        $now = now();

        $user->forceFill([
            'last_check_in_at' => now(),
        ])->save();

        CheckInLog::create([
            'user_id' => $user->id,
            'checked_in_at' => $now,
        ]);

        return $user->fresh();
    }
}
