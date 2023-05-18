<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LogoutController extends Controller
{
    public function logout(Request $request){

        $request->user()->currentAccessToken()->delete();

        return Response('Logged Out Successfully');

    }
}
