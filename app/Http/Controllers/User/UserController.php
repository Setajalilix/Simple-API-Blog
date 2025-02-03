<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Repositories\Contracts\IUser;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $user;
    public function __construct(IUser $user)
    {
        $this->user = $user;
    }
    public function index(){
        $users=$this->user->all();
        return Userresource::collection($users);
    }
}
