<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Spatie\Tags\HasTags;

class Design extends Model
{
    use HasFactory , HasTags;

    protected $fillable = [
        'user_id',
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
