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
    //For Getting Data
    Route::get('get-links', [HomeController::class,'links']);
    Route::get('get-user', [LoginController::class,'get_user']);
    Route::post('profile-get', [ProfileUpdateController::class,'profile_get']);
    //For Changes
    Route::post('profile-pic', [ProfileUpdateController::class,'updateProfilePic']);
    Route::post('profile',[ProfileUpdateController::class,'profileUpdate']);
    Route::any('change_password',[ChangePasswordController::class,'changePassword']);
    Route::post('logout',[LogoutController::class,'logout']);
    Route::delete('tokens_delete',[LogoutController::class,'logoutAll']);

 });

 /* Authentication */
 Route::any('signup',[RegisterController::class,'register']);
 Route::any('login',[LoginController::class,'login'])->name('login');

 /* Reset */
 Route::any('reset_password', [ResetPasswordController::class,'apiReset']);
 Route::any('reset', [ResetPasswordController::class,'apiSendResetLinkEmail'])->name('password.reset');


