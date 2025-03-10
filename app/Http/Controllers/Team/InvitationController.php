<?php

namespace App\Http\Controllers\Team;

use App\Http\Controllers\Controller;
use App\Mail\SendInvitationToJoin;
use App\Models\Invitation;
use App\Models\Team;
use App\Repositories\Contracts\IInvitation;
use App\Repositories\Contracts\ITeam;
use App\Repositories\Contracts\IUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class InvitationController extends Controller
{
    private IInvitation $invitation;
    private $teams;
    private IUser $users;

    public function __construct(IInvitation $invitation,ITeam $teams,IUser $users)
    {
        $this->invitation = $invitation;
        $this->teams = $teams;
        $this->users = $users;
    }

    public function invite($teamId, Request $request)
    {
        $team = $this->teams->find($teamId);
        $request->validate(['email' => 'required|email']);

        $user = Auth::user();
        if (!$user->isOwnerOfTeam($team)) {
            return response(['email' => 'you are not owner of team'], 401);
        }
        if ($team->hasPendingInvite($request->email)) {
            return response(['email' => 'email already has pending invite'], 422);
        }

        $recipient = $this->users->findByEmail($request->email);
        if (!$recipient) {
            $this->createInvitation(false, $team, $request->email);
            return response(['email' => 'email send'], 200);
        }
        if ($team->hasPendingInvite($recipient)) // error
        {
            return response(['email' => 'user is member of team'], 422);
        }
        $this->createInvitation(true, $team, $request->email);
        return response(['email' => 'invitation send'], 200);
    }

    protected function createInvitation(bool $user_exist, Team $team, string $email)
    {
        $invitation = $this->invitation->create([
            'team_id' => $team->id,
            'sender_id' => Auth::id(),
            'recipient_email' => $email,
            'token' => md5(uniqid(microtime())),
        ]);

        Mail::to($email)->send(new SendInvitationToJoin($invitation, $user_exist));
    }

    public function resend($id)
    {
        $invitation = $this->invitation->find($id);
        $this->authorize('resend', $invitation);
        $recipient = $this->users->findByEmail($invitation->recipient_email);
        Mail::to($invitation->recipient_email)->send(new SendInvitationToJoin($invitation, !is_null($recipient)));
        return response(['email' => 'invitation resend'], 200);
    }

    public function respond($id, Request $request)
    {
        $request->validate(
            ['token' => 'required'],
            ['decision' => 'required']
        );
        $invitation = $this->invitation->find($id);
        $token = $request->token;
        $decision = $request->decision;

        $this->authorize('respond', $invitation);
        if ($token !== $invitation->token) {
            return response(['error' => 'invalid token'], 422);
        }
        if ($decision === 'deny') {
            $this->invitation->addUserToTeam($invitation->team, Auth::id());
        }

        $invitation->delete();
        return response(['message' => 'invitation declined'], 200);
    }

    public function destroy($id)
    {
        $invitation = $this->invitation->find($id);
        $this->authorize('delete', $invitation);
        $invitation->delete();
        return response(['message' => 'invitation deleted'], 200);
    }


}
