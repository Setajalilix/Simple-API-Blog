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
        return [
            'id' => $this->id,
            'name' => $this->name,
            'username' => $this->username,
            'email' => $this->email,
            'tagline' => $this->tagline,
            'about' => $this->about,
            'available_to_hire' => $this->available_to_hire,
            'created_dates' => [
                'created_at_human' => $this->created_at->diffForHumans(),
            ]
        ];
    }
}
