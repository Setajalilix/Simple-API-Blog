<?php

namespace App\Models\Traits;

use App\Models\Like;

trait Likable
{
    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }
}
