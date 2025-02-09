<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


class DesignResource extends JsonResource
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
            'user' => new UserResource($this->user),
            'team' => $this->team ? ['name' => $this->team->name,] :null,
            'title' => $this->title,
            'description' => $this->description,
            'likes'=>$this->likes()->count(),
            'slug' => $this->slug,
            'is_alive' => $this->is_live,
            'tag_list' => [
                'tags' => $this->tags->pluck('name')
            ],
            'image' => $this->getImages(),
            'created_dates' => [
                'created_at_human' => $this->created_at->diffForHumans(),
            ]


        ];
    }
}
