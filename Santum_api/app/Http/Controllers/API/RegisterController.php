<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;


class RegisterController extends Controller
{
    public function register(Request $request){

        $validator = Validator::make($request->all(),[
            'fname' => 'required',
            'lname' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'c_password' => 'required_with:password|same:password|min:6',
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],

        ]);
        if($validator->fails()){
            return Response(['Message' => $validator->errors()],401);
        }
        $data = $request->all();
        $user = User::create($data);

        if ($user) {
            
            return response([
                'message' => 'Data Saved Successfully',
                'status' => 201,
            ], 201);
        } else {
            return response([
                'status' => 404,
                'message' => 'Something Went Wrong',
            ], 404);
        }
    }
}
