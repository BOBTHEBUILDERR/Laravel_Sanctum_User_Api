<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\AuthController;
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
   Route::any('change_password',[UserController::class,'changePassword']);
   Route::post('logout',[UserController::class,'logout']);
   Route::post('tokens_delete',[UserController::class,'logoutAll']);
   Route::post('profile',[UserController::class,'profileUpdate']);
   Route::post('profile-pic', [UserController::class,'updateProfilePic']);
   Route::post('profile-get', [UserController::class,'profile_get']);
   Route::get('get-links', [UserController::class,'links']);
   Route::get('get-user', [UserController::class,'get_user']);

});
Route::any('signup',[UserController::class,'signup']);
Route::any('login',[UserController::class,'login'])->name('login');
// Route::any('forget_password',[UserController::class,'submitForgetPasswordForm']);
// Route::any('/password/reset', [UserController::class,'resetPassword'])->name('password.reset');
Route::any('reset_password', [UserController::class,'apiReset']);
Route::any('reset', [UserController::class,'apiSendResetLinkEmail'])->name('password.reset');




Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);



