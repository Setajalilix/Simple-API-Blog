<?php

namespace App\Repositories\Eloquent;

use App\Models\Team;
use App\Models\Traits;
use App\Repositories\Contracts\ITeam;


class TeamRepository extends BaseRepository implements ITeam
{
    public function model(): string
    {
        return Team::class;
    }
    public function fetchUserTeam()
    {
        return auth()->user()->Teams;
    }
}
