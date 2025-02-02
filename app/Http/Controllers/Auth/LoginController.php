<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use function Laravel\Prompts\error;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;


    protected function attemptLogin(Request $request)
    {
        $Token = $this->guard()->attempt($this->credentials($request));
        if (!$Token) {
            return false;
        }
        $user = $this->guard()->user();
        if ($user instanceof MustVerifyEmail && !$user->hasVerifiedEmail()) {
            return false;
        }
        $this->guard()->setToken($Token);
        return true;
    }

    protected function sendLoginResponse(Request $request)
    {
        $this->clearLoginAttempts($request);
        $Token = (string)$this->guard()->getToken();

        $expire = $this->guard()->getpayload()->get('exp');
        return response()->json([
            'token' => $Token,
            'expires_in' => $expire,
            'token_type' => 'bearer',

        ]);
    }


    protected function sendFailedLoginResponse(Request $request)
    {
        $user = $this->guard()->user();
        if ($user instanceof MustVerifyEmail && !$user->hasVerifiedEmail()) {
            return response()->json([
                'errors' => [
                    'message' => 'you need to verify your email address',
                ]
            ], 422);
        }
        throw ValidationException::withMessages([
            $this->username() => "invalid credentials",
        ]);
    }

    public function logout(Request $request)
    {
        $this->guard()->logout();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

}
