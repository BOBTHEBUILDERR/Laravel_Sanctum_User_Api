<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;


class ResetPasswordController extends Controller
{
    public function apiSendResetLinkEmail(Request $request)
    {
        $validator = Validator::make($request->all(),['email' => 'required|email']);
        if($validator->fails()){
            return Response(['message' => $validator->errors()],401);
        }

        $status = Password::sendResetLink(
            $request->only('email')
        );
        // dd($status);
        if ($status === Password::RESET_LINK_SENT) {
            return response()->json(['message' => 'Reset link sent'], 200);
        } else {
            return response()->json(['message' => 'Failed to send reset link'], 400);
        }
    }
    public function apiReset(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);
        if($validator->fails()){
            return Response(['message' => $validator->errors()],401);
        }
        // dd("asd");
        $response = Password::reset(
            $request->only('email', 'password', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => $password
                ])->save();
            }
        );

        if ($response === Password::PASSWORD_RESET) {
            return response()->json(['message' => 'Password reset successful'], 200);
        } else {
            return response()->json(['message' => 'Failed to reset password'], 400);
        }
    }
}
