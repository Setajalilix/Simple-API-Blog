<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'owner_id',
        'slug',
        'image',
    ];

    protected static function boot()
    {
        parent::boot(); //required

        static::created(function ($team) {
            $team->members()->attach(auth()->id());
        });
        static::deleting(function ($team) {
            $team->members()->sync([]);
        });
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function designs()
    {
        return $this->hasMany(Design::class);
    }

    public function members()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    public function invitations(){
        return $this->hasMany(Invitation::class);
    }

    public function hasPendingInvite($email)
    {
         return (bool)$this->invitations()->where('recipient_email', $email)->count();
    }

    public function hasUser($user)
    {
        return (bool)$this->members()->where('user_id', $user->id)->first();
    }
}
