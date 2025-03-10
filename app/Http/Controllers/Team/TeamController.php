<?php

namespace App\Http\Controllers\Team;

use App\Http\Controllers\Controller;
use App\Http\Resources\TeamResource;
use App\Repositories\Contracts\IInvitation;
use App\Repositories\Contracts\ITeam;
use App\Repositories\Contracts\IUser;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use mysql_xdevapi\Collection;

class TeamController extends Controller
{
    protected $team;
    protected $user;
    protected $invitation;

    public function __construct(ITeam $team, IUser $user, IInvitation $invitation)
    {
        $this->team = $team;
        $this->user = $user;
        $this->invitation = $invitation;
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
        $this->team->update($teamId, [
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

    public function removeFromTeam($teamId, $userId)
    {
        $team = $this->team->find($teamId);
        $user = $this->user->find($userId);

        if ($user->isOwnerOfTeam($team)) {
            return response()->json([
                'message' => 'You cannot remove your own team'
            ], 401);
        }
        if (!auth()->user()->isOwnerOfTeam($team) && Auth()->id() !== $user->id) {
            return response()->json([
                'message' => 'You cannot remove other users'
            ], 401);
        }
        $this->invitation->removeUserFromTeam($team, $user);
        return response()->json([
            'message' => 'removed'
        ], 200);
    }

}
