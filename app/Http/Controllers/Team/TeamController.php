<?php

namespace App\Http\Controllers\Team;

use App\Http\Controllers\Controller;
use App\Http\Resources\TeamResource;
use App\Repositories\Contracts\ITeam;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use mysql_xdevapi\Collection;

class TeamController extends Controller
{
    protected $team;

    public function __construct(ITeam $team)
    {
        $this->team = $team;
    }

    public function index()
    {
        return TeamResource::collection($this->team->all());
    }
    public function findBySlug($slug)
    {

    }
    public function findById($teamId)
    {
        $team = $this->team->find($teamId);
        return new TeamResource($team);
    }
    public function fetchUserTeam()
    {
        $teams = $this->team->fetchUserTeam();
        return TeamResource::collection($teams);
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'nullable | string | max:255 | min:10',
        ]);
            $team = $this->team->create([
                'name' => $request->name,
                'description' => $request->description,
                'owner_id' => auth()->id(),
                'slug' => Str::slug($request->name),
            ]);
            return new TeamResource($team);

    }
    public function update($teamId, Request $request)
    {
        $team = $this->team->find($teamId);

        $this->authorize('update', $team);

        $request->validate([
            'name' => 'required',
            'description' => 'nullable | string | max:255 | min:10',
        ]);
        $this->team->update($teamId,[
            'name' => $request->name,
            'description' => $request->description,
            'owner_id' => auth()->id(),
            'slug' => Str::slug($request->name),
        ]);
        return new TeamResource($team);

    }
    public function destroy($teamId)
    {
        $team = $this->team->find($teamId);
        $this->authorize('delete', $team);
    }


}
