<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CheckInLogResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'checked_in_at' => $this->checked_in_at->toISOString(),
            'timestamp' => $this->checked_in_at->timestamp,
            'human' => $this->checked_in_at->diffForHumans(),
        ];
    }
}
