<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogoutController extends Controller
{
    public function logout(Request $request){

        // $request->user()->token()->revoke();
        $user  = $request->user()->currentAccessToken()->delete();
        if ($user ){
            return Response([
                'status' => 201,
                'message' => 'Logged Out Succesfully',
                ], 201);
        }
        else{
            return Response([
                'status' => 404,
                'message' => 'Something went Wrong',
                 ], 404);
        }
    }
    public function logoutAll(Request $request)
    {
        //Revokes Same User Tokens
        $user = $request->user();
        $user->tokens()->delete();
        return response()->json(['message' => 'Tokens Deleted Succesfully'], 200);
    }
}
