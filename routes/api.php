<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\registerController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\User\MeController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Design\UploadController;
use App\Http\Controllers\Design\DesignController;
use \App\Http\Controllers\Design\CommentController;
use \App\Http\Controllers\Team\TeamController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::get('/', function () {
    return response()->json(['message' => 'hello'], 200);
});


Route::get('users', [UserController::class, 'index']);
Route::get('designs', [DesignController::class, 'index']);
Route::get('teams', [TeamController::class, 'index']);
Route::get('teams/{slug}', [TeamController::class, 'findBySlug']);

Route::group(['middleware' => 'auth:api'], function () {

    Route::get('me', [MeController::class, 'getMe']);
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');

    Route::prefix('setting')->group(function () {
        Route::put('UpdateProfile', [MeController::class, 'UpdateProfile']);
        Route::put('UpdatePassword', [MeController::class, 'UpdatePassword']);
    });

    Route::prefix('design')->group(function () {
        Route::post('/', [UploadController::class, 'Upload']);
        Route::put('update/{id}', [DesignController::class, 'update']);
        Route::delete('delete/{id}', [DesignController::class, 'destroy']);
        //like
        Route::post('{designId}', [DesignController::class, 'like']);
        Route::get('{designId}', [DesignController::class, 'isLikedByUser']);


    });

    Route::prefix('comment')->group(function () {
        Route::post('{designId}', [CommentController::class, 'store']);
        Route::put('{id}', [CommentController::class, 'update']);
        Route::delete('{id}', [CommentController::class, 'destroy']);
    });

    Route::prefix('team')->group(function () {
        Route::get('/{TeamId}', [TeamController::class, 'findById']);
        Route::post('myTeam', [TeamController::class, 'fetchUserTeam']);
        Route::post('/create', [TeamController::class, 'store']);
        Route::put('{TeamId}', [TeamController::class, 'update']);
        Route::delete('{TeamId}', [TeamController::class, 'destroy']);
    });





});

Route::group(['middleware' => 'guest:api'], function () {

    Route::prefix('Verification')->group(function () {
        Route::post('verify', [VerificationController::class, 'verify'])->name('verification.verify');
        Route::post('resend', [VerificationController::class, 'resend']);
    });

    Route::post('register', [registerController::class, 'register']);


    Route::post('login', [LoginController::class, 'login'])->name('login');

});
