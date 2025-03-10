<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model
{
    use HasFactory;
    use softDeletes;

    protected $fillable = [
        'user_id',
        'chat_id',
        'body',
        'last_read'
    ];

    protected $touches = ['chat'];


    public function getBodyAttribute($value)
    {
        if($this->trashed())
        {
            if(!auth()->check()) return null;
            return 'deleted';
        }
        return $value;
    }
    public function chat()
    {
        return $this->belongsTo(Chat::class);
    }
    public function sender()
    {
        return $this->belongsTo(User::class, 'user_id');
    }


}
