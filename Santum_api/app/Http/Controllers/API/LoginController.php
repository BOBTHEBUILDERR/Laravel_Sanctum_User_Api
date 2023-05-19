<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LoginController extends Controller
{
    public function login(Request $request)
{
    $validator = Validator::make($request->all(), [
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if ($validator->fails()) {
        return response(['message' => $validator->errors()], 401);
    }

    if (Auth::attempt($request->only('email', 'password'))) {
        $user = Auth::user();
        $token = $user->createToken('auth_token')-> ;


        return response()->json([
            'status' => 201,
            'token' => $token,
            'message' => 'Logged In Successfully'
        ], 201);
    } else {
        return response()->json([
            'status' => 404,
            'message' => 'Log In Failed',
        ], 404);
        }
    }
    public function get_user(Request $request){
        $user = $request->user();
        if($user){
            $data = [
                'status' => 201,
                'message' => 'User details',
                'data' => $user,

            ];
            return response()->json($data, 201);

        }else{
        return response()->json(['User Not Found'], 404);

        }


    }
}
