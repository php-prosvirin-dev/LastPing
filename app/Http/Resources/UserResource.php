<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $interval_minutes = data_get(
            $this->settings,
            'check_in.interval_minutes',
            60
        );

        return [
            'interval_seconds' => $interval_minutes * 60,
            'last_check_in_at' => $this->last_check_in_at?->timestamp,
        ];
    }
}
