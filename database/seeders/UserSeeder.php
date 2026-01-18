<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            'settings' => [
                'check_in' => [
                    'interval_minutes' => 60,
                    'grace_period_minutes' => 10,
                    'last_triggered_at' => null,
                ],
                'notifications' => [
                    'enabled' => true,
                    'siblings' => [
                        [
                            'name' => 'John Doe',
                            'email' => 'john@example.com',
                        ],
                    ],
                    'subject' => 'Emergency alert',
                    'message' => 'Please check on me.',
                ],
                'data_handling' => [
                    'purge_method' => 'archive',
                    'delay_minutes' => 30,
                ],
            ],
        ];


        User::updateOrCreate(
            [
                'email' => 'demo@lastping.app',
                ...$settings,
            ],
            [
                'name' => 'Demo User',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
    }
}
