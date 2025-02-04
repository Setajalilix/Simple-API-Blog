<?php

namespace App\Models\Traits;

use App\Models\Like;

trait Likable
{
    public static function bootLikable()
    {
        static::deleting(function ($model) {
            $model->removeLikes();
        });
    }

    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    public function like(): void
    {
        if(auth()->check() && !$this->isLikeByUser(auth()->id()));
        {
            $this->likes()->create([
                'user_id' => auth()->id()
            ]);
        }

    }

    public function unlike(): void
    {
        if(auth()->check() && $this->isLikeByUser(auth()->id())){
            $this->likes()->where(['user_id' => auth()->id()])->delete();
        }
    }

    public function isLikeByUser($user_id): bool
    {
        return (bool)$this->likes()->where('user_id', $user_id)->count();
    }

    public function removeLikes(): void
    {
        if($this->likes()->count())
            $this->likes()->delete();
    }
}
