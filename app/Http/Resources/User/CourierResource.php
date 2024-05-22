<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourierResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'preferences' => [
                'is_active_courier' => $this->preferences['is_active_courier'] ?? false,
            ],
            'total_picked_up' => $this->whenNotNull($this->total_picked_up),
            'total_external' => $this->whenNotNull($this->total_external),
            'total_main' => $this->whenNotNull($this->total_main),
            'total_main_wb_approved' => $this->whenNotNull($this->total_main_wb_approved),
        ];
    }
}
