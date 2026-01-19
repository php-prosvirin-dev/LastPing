<?php

namespace App\Console\Commands;

use App\Enums\UserMonitoringState;
use App\Mail\EmergencyNotificationMail;
use App\Mail\WarningMail;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class ProcessMonitoringStates extends Command
{
    protected $signature = 'lastping:process';

    protected $description = 'Process monitoring state transitions';

    public function handle(): void
    {
        $now = now();

        User::whereNotNull('last_check_in_at')->get()->each(function (User $user) use ($now) {
            match ($user->monitoring_state) {
                UserMonitoringState::ACTIVE => $now->greaterThan($user->graceExpiresAt())
                    ? $this->toWarning($user)
                    : null,

                UserMonitoringState::WARNING => $now->greaterThan($user->graceExpiresAt())
                    ? $this->toTriggered($user)
                    : null,

                default => null,
            };
        });
    }

    protected function toWarning(User $user): void
    {
        if ($user->warning_sent_at) {
            return;
        }

        $user->markWarning();

        Mail::to($user->email)->queue(
            new WarningMail($user)
        );
    }

    protected function toTriggered(User $user): void
    {
        if ($user->emergency_notified_at) {
            return;
        }

        $user->markTriggered();

        foreach ($user->settings['notifications']['siblings'] as $contact) {
            Mail::to($contact['email'])->queue(
                new EmergencyNotificationMail($user, $contact)
            );
        }
    }
}
