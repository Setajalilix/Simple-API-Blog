<?php

namespace App\Models;

use App\Models\Traits\Likable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Spatie\Tags\HasTags;

class Design extends Model
{
    use HasFactory , HasTags , Likable;

    protected $fillable = [
        'user_id',
        'team_id',
        'title',
        'description',
        'image',
        'slug',
        'disk',
        'upload_successful',
        'is_live'
    ];



    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function Team()
    {
        return $this->belongsTo(Team::class);
    }
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable')->orderBy('created_at','asc');
    }

    public function getImages(): array
    {
        return [
            'thumbnail' => $this->getImagePath('Thumbnail'),
            'original' => $this->getImagePath('Original'),
            'large' => $this->getImagePath('Large'),
        ];
    }

    protected function getImagePath($size): string
    {
        return Storage::disk($this->attributes['disk'])->url("Uploads/Designs/$size/" . $this->attributes['image']);

    }
}
