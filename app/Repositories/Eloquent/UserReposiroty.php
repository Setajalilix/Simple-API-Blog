<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\Contracts\IUser;

class UserReposiroty extends BaseRepository implements IUser
{

    public function model(): string
    {
        return User::class;
    }
}
