<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class ChangePasswordController extends Controller
{
    public function changePassword(Request $request) {

        $validator = Validator::make($request->all(),[
			'old_password' => 'required',
			'password' => 'required',
            'c_password' => 'required_with:password|same:password|min:6',
		]);
        if($validator->fails()){
            return Response(['message' => $validator->errors()],401);
        }
        $user = $request->user();
        $oldPassword = $request->input('old_password');

        if (!Hash::check($oldPassword, $user->password)) {
            return response()->json(['message' => 'Old password is incorrect'], 401);
        }

        $user->password = $request->input('password');
        $user->save();


        return response()->json(['message' => 'Password has been changed successfully']);
    }

}
