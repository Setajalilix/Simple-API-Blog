<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class ChatResource extends JsonResource
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
            'created_dates' => [
                'created_at_human' => $this->created_at->diffForHumans(),
            ],
            'is_unread'=>$this->isUnreadForUser(Auth::id()),
            'latest_message' => new MessageResource($this->latest_message),
            'participant'=>UserResource::collection($this->participants)
        ];
    }
}
