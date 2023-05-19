<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;


class ProfileUpdateController extends Controller
{
    public function profileUpdate(Request $request)
    {

        $user = $request->user();

        $validator = Validator::make($request->all(),[
            'fname' => 'required|string|max:255',
            'lname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            // add more validation rules for other fields
        ]);
        if($validator->fails()){
            return Response(['message' => $validator->errors()],401);
        }

        $user->fname = $request->fname;
        $user->lname = $request->lname;
        $user->email = $request->email;
        // update other fields as needed
        $success = $user->save();

        if($success){
            return response()->json(['message' => 'Profile updated successfully'], 200);
            }else{
            return response()->json(['message' => 'Failed to update profile'], 400);
        }
    }

    public function updateProfilePic(Request $request)
    {

        $user = $request->user();

        $validator = Validator::make($request->all(),[
            'profile_pic' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        if($validator->fails()){
            return Response(['message' => $validator->errors()],401);
        }

        $image = $request->file('profile_pic');
        $filename = $user->id . '_' . time() . '.' . $image->getClientOriginalExtension();

        Storage::disk('public')->putFileAs('profile_pics', $image, $filename);

        $user->avatar = $filename;
        if($user->save()){
            return response()->json(['message' => 'Profile picture updated successfully'], 200);
        }else{
            return response()->json(['message' => 'Failed to update picture picture'], 400);
        }
    }
    public function profile_get(Request $request){
        $user = $request->user();

        $filename = $user->avatar; // Assuming the avatar filename is stored in the 'avatar' attribute of the user model
        $path = 'profile_pics/' . $filename;

        // Check if the file exists
        if (Storage::disk('public')->exists($path)) {
            // Retrieve the file
            $file = Storage::disk('public')->get($path);

            // You can then process or display the file as needed
            // For example, if you want to return the file as a response, you can use:
            return response($file, 200)->header('Content-Type', Storage::disk('public')->mimeType($path));
        } else {
            return response()->json(['message' => 'Missing or failed to get profile picture'], 400);
        }

    }
}
