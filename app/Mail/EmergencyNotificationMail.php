<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmergencyNotificationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public User $user;
    public string $message;

    /**
     * Create a new message instance.
     */
    public function __construct(
        User $user,
        string $subject,
        string $message
    ) {
        $this->user = $user;
        $this->subject($subject);
        $this->message = $message;
    }

    /**
     * Build the message.
     */
    public function build(): self
    {
        return $this->markdown('emails.emergency')
            ->with([
                'user' => $this->user,
                'messageText' => $this->message,
                'lastCheckIn' => $this->user->last_check_in_at,
            ]);
    }
}
