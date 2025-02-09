<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TeamResource extends JsonResource
{


    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [

            'name' => $this->name,
            'owner' => new UserResource($this->owner),
            'description' => $this->description,
            'slug' => $this->slug,
            'total_members'=>$this->members->count(),
            'members'=>UserResource::collection($this->members),
            'designs'=>DesignResource::collection($this->designs),
            'created_dates' => [
                'created_at_human' => $this->created_at->diffForHumans(),
            ]
        ];
    }
}
