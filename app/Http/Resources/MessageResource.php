<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
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
            'body' => $this->body,
            'sender' => new UserResource($this->sender_id),
            'dates'=> $this->created_at->diffForHumans(),
            'deleted'=>$this->trashed(),
            'last_read'=>$this->last_read,
        ];
    }
}
