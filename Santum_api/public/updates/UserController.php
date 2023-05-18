<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;


class UserController extends Controller
{
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
    public function signup(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'fname' => 'required',
            'lname' => 'required',
            'email' => '    required|email|unique:users',
            'password' => 'required|min:6',
            'c_password' => 'required_with:password|same:password|min:6',
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],

        ]);


        if($validator->fails()){
            return Response(['message' => $validator->errors()],401);
        }

        $data = $request->all();
        $user = User::create($data);
        if($user){
            // $token = $user->createToken('Personal Access Token')->plainTextToken;
            $data = [
                // 'token' => $token,
                'message' => 'Data Saved Successfully',
                'status' => 201,

            ];
            return response()->json($data, 201);

        }else{
            $data = [
                'status' => 404,
                'message' => 'Something Went Wrong',
            ];
            return response()->json($data, 404);
        }


    }

    public function login(Request $request) {
		$validator = Validator::make($request->all(),[
			'email' => 'required|email',
			'password' => 'required',
		]);
        if($validator->fails()){
            return Response(['message' => $validator->errors()],401);
        }

        $credentials = $request->only('email', 'password');



        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('auth_token')->plainTextToken;
            $data = [
                'status' => 201,
                'token' => $token,
                'message' => 'Logged In Successfully'
            ];
            return response()->json($data, 201);
        }else{
            $data = [
                'status' => 404,
                'message' => 'Log In Failed',
            ];
            return response()->json($data, 404);
        }
	}

    public function submitForgetPasswordForm(Request $request)
    {

        $validator = Validator::make($request->all(),[
            'email' => 'required|email|exists:users',
        ]);
        if($validator->fails()){
            return Response(['message' => $validator->errors()],401);
        }

          $status = Password::sendResetLink(
			$request->only('email')
		);
        if($status === Password::RESET_LINK_SENT) {
			return response()->json(['message' => __($status)], 200);
		} else {
			throw ValidationException::withMessages([
				'email' => __($status)
			]);
		}

    }

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

    public function requestToken(Request $request): string
    {
        $validator = Validator::make($request->all(),[
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        return $user->createToken($request->device_name)->plainTextToken;
    }

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
    public function links(){
        
        $links = [
            ['name'=>'oahu', 'links'=>'https://shor.by/oahu'],
            ['name'=>'lasvegas', 'links'=>'https://shor.by/lasvegas'],
            ['name'=>'bigislandhawaii', 'links'=>'https://shor.by/bigislandhawaii'],
            ['name'=>'newyorknow', 'links'=>'https://shor.by/newyorknow'],
            ['name'=>'phoenixaz', 'links'=>'https://shor.by/phoenixaz'],
            ['name'=>'destin','links'=>'https://shor.by/destin'],
            ['name'=>'mauinow', 'links'=>'https://shor.by/mauinow'],
            ['name'=>'laketahoenow', 'links'=>'https://shor.by/laketahoenow'],
            ['name'=>'sedona', 'links'=>'https://shor.by/sedona'],
            ['name'=>'Kauai', 'links'=>'https://shor.by/Kauai']
        ];

        $data = [
                'status' => 201,
                'message' => 'Available Limks',
                'links' => $links,
            ];
        return response()->json($data, 200);
    }

    public function logout(Request $request)
    {
        // $user = Auth::user()->token();
        // $user->revoke();

        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged Out Succesfully'], 200);
    }

    public function logoutAll()
    {


        $user = Auth::user();
        Session::flush();
        Auth::logout();
        $user->tokens()->delete();
        return response()->json(['message' => 'Tokens Deleted Succesfully'], 200);
    }

}
