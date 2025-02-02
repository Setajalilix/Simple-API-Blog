<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use http\Env\Response;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class VerificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

    public function verify(Request $request, User $user)
    {
        // check if the URL is valid
        if (!URL::hasValidSignature($request)) {
            return Response()->json(['error' => 'This URL is not verified.'], 422);
        }

        // check if the user has already validated
        if ($user->hasVerifiedEmail()) {
            return Response()->json(['error' => 'Email already verified.'], 422);
        }

        $user->markEmailAsVerified();
        event(new Verified($user));
        return Response()->json(['message' => 'Email verified.'], 200);
    }

    public function resend(Request $request, User $user)
    {

    }
}
