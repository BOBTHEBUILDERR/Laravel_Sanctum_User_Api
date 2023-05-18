<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\API\LoginController;
use App\Http\Controllers\API\LogoutController;
use App\Http\Controllers\API\ResetPasswordController;
use App\Http\Controllers\API\ChangePasswordController;
use App\Http\Controllers\API\ProfileUpdateController;
use App\Http\Controllers\API\HomeController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::group(['middleware' => 'auth:sanctum'], function(){
    Route::get('test',function(){
     return 'hello';
    });
    Route::any('change_password',[APIController::class,'changePassword']);
    Route::post('logout',[LogoutController::class,'logout']);
    Route::post('tokens_delete',[APIController::class,'logoutAll']);
    Route::post('profile',[APIController::class,'profileUpdate']);
    Route::post('profile-pic', [APIController::class,'updateProfilePic']);
    Route::post('profile-get', [APIController::class,'show']);
    Route::get('get-links', [APIController::class,'links']);
    Route::get('get-user', [APIController::class,'get_user']);
 
 });
 Route::any('signup',[RegisterController::class,'register']);
 Route::any('login',[LoginController::class,'login'])->name('login');
 // Route::any('forget_password',[APIController::class,'submitForgetPasswordForm']);
 // Route::any('/password/reset', [APIController::class,'resetPassword'])->name('password.reset');
 Route::any('reset_password', [APIController::class,'apiReset']);
 Route::any('reset', [APIController::class,'apiSendResetLinkEmail'])->name('password.reset');
 Route::post('/tokens/create', function (Request $request) {
    $token = $request->user()->createToken($request->token_name);
    return ['token' => $token->plainTextToken];
});