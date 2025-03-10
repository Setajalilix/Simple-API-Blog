<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;

    public function getLatestMessagesAttribute()
    {
        return $this->messages()->latest()->first(); //
    }

    public function users()
    {
        $this->belongsToMany(User::class, 'participant');
    }

    public function messages()
    {
        $this->hasMany(Message::class);
    }

    public function isUnreadForUser($userId): bool
    {
        return (bool)$this->messages->whereNull('last_read')->where('user_id', '<>', $userId)->count();
    }

    public function markAsReadForUser($userId)
    {
        return $this->messages->whereNull('last_read')->where('user_id', '<>', $userId)->update(['last_read' => Carbon::now()]);
    }
}
