<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [];

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_check_in_at' => 'datetime',
        ];
    }

    public function getSettingsAttribute($value): array
    {
        $stored = [];

        if (is_string($value)) {
            $stored = json_decode($value, true) ?? [];
        } elseif (is_array($value)) {
            $stored = $value;
        }

        return array_replace_recursive([
            'check_in' => [
                'interval_minutes' => 1440,
                'grace_period_minutes' => 120,
                'last_triggered_at' => null,
            ],
            'notifications' => [
                'enabled' => true,
                'siblings' => [],
                'subject' => 'Emergency notification',
                'message' => 'I may be unable to respond. Please check on me.',
            ],
            'data_handling' => [
                'purge_method' => 'archive',
                'delay_minutes' => 0,
            ],
        ], $stored ?? []);
    }

    /**
     * @return HasMany
     */
    public function checkIns()
    {
        return $this->hasMany(CheckInLog::class);
    }
}
