<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Rules\checkSamePassword;
use Illuminate\Http\Request;
use app\Rules\MatchPassword;

class MeController extends Controller
{
    public function getMe()
    {
        if (auth()->check()) {
            return new UserResource(auth()->user());
        }

        return response()->json(null, 401);
    }

    public function UpdateProfile(Request $request): UserResource
    {
        $user = auth()->user();
        $request->validate([
            'name' => 'required',
            'tagline' => 'required',
            'about' => 'min:10',

        ]);
        $user->update([
            'name' => $request->name,
            'tagline' => $request->tagline,
            'about' => $request->about
        ]);
        return new UserResource($user);
    }

    public function UpdatePassword(Request $request): UserResource
    {
        $request->validate([
            'current_password' =>['required', new MatchPassword],
            'password' =>['required','min:8','confirmed', new checkSamePassword],
        ]);
        $request->user()->update([
            'password' =>bcrypt($request->password)
        ]);
        return new UserResource($request->user());
    }
}
