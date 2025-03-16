<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;





class usercontroller extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['userLogin', 'userRegister']]);
    }
    //user register
    //userRegister

    public function userRegister (Request $request)
{
    $validator = Validator::make($request->all(), [
        'username' => 'required|string|min:3|max:255',
        'name' => 'required|string|min:3|max:255',
        'phone_number' => 'required|string',
        'email' => 'required|string|email',
        'password' => 'required|string',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'message' => 'Validation error',
            'errors' => $validator->errors(),
        ], 422);
    }

    $user = new User();
    $user->username = $request->input('username');
    $user->name = $request->input('name');
    $user->email = $request->input('email');
    $user->phone_number = $request->input('phone_number');
    $user->password = Hash::make($request->input('password'));
    $user->save();
    
    return response()->json([
        'message' => 'User successfully registered',
        'user' => $user,
    ], 201);
}
    
// user login 
protected function createNewToken($token)
{

    return response()->json([
        'message' => 'login sucessfully',
        'status' => 'true',
        'access_token' => $token,
        'token_type' => 'bearer',
        'expires_in' => auth('api')->factory()->getTTL() * 60,
        'user' => Auth::guard('api')->user()
    ]);
}
//USER LOGIN
public function userLogin(Request $request)
{


    $validator = Validator::make($request->all(), [
        'username' => 'required|string',
        'password' => 'required|string',
    ]);

    if ($validator->fails()) {
        return response()->json($validator->errors(), 422);
    }
    

    if (!$token = $this->guard()->attempt($validator->validated())) {
        return response()->json([
            'message' => 'error please check your user name and password and try again',
            'status' => 'false'
        ], 401);
    }
    return $this->createNewToken($token);
}

//user logout
public function userLogout()
    {
        $this->guard()->logout();

        return response()->json([
            'message' => 'User successfully signed out',
            'status' => 'true'
        ]);
    }

protected function guard()
{
    return Auth::guard('api');
}

//update profile 
public function userupdate (Request $request)
{
    $user = Auth::user(); // Get the authenticated user
    
    // Validate the input data
    $validator = Validator::make($request->all(), [
        'name' => 'nullable|string|min:3|max:255',
        'jop_title' => 'nullable|string',
        'bio' => 'nullable|string',
        'avatar' => 'nullable|image',
        'background' => 'nullable|image',

    ]);

    if ($validator->fails()) {
        return response()->json([
            'message' => 'Validation error',
            'errors' => $validator->errors(),
        ], 422);
    }
/** */
    // Update the user's information
    $user->name = $request->input('name', $user->name);
    $user->jop_title = $request->input('jop_title', $user->jop_title);
    $user->bio = $request->input('bio', $user->bio);

    if ($request->hasFile('avatar')) {
        $avatar = $request->file('avatar');
        $filename = time() . '_' . $avatar->getClientOriginalName();
        Storage::disk('public')->putFileAs('avatars', $avatar, $filename);
        $user->avatar = $filename;
    }

    if ($request->hasFile('background')) {
        $background = $request->file('background');
        $filename = time() . '_' . $background->getClientOriginalName();
        Storage::disk('public')->putFileAs('background', $background, $filename);
        $user->background = $filename;
    }

    $user->save();

    return response()->json([
        'message' => 'User updated successfully',
        'user' => $user,
    ], 200);
}

//SHOW USER BY USERNAME
public function show(Request $request)
{
    $user = Auth::user(); // Get the authenticated user

        // $validatedData = $request->validate([
        //     'username' => 'required'
        // ]);

    // $user = User::where('username', $request->input('username'))->first();

    // if (!$user) {
    //     return response()->json([
    //         'message' => 'User not found'
    //     ], 404);
    // }
    // $user = User::find(1);
    // $avatarPath = $user->avatar;
    // $avatarUrl = asset('storage/' . $avatarPath);
    
    return response()->json([
        'user' => $user,
        // 'avatar' => $avatarUrl,
    ], 200);
     
}

//SHOW ALL USERS
public function showall()
{
    
    $users = User::all();
    return response()->json([
        'users' => $users
    ], 200);
}

// public function updateAvatar(Request $request)
// {
//     $user = auth()->user();
    

//     if ($request->hasFile('avatar')) {
//         $avatar = $request->file('avatar');
//         $filename = time() . '.' . $avatar->getClientOriginalExtension();
//         $avatar->storeAs('public/avatars', $filename);
//         $user->avatar = $filename;
//         $user->save();
//     }
//     $path = $request->file('avatar')->store('avatars');
 


    // return response()->json([
    //     'user' => $user,
    //     'avatar' =>  $path
    //     ,
    // ], 200);

// }

public function uploadAvatar(Request $request)
{
    $user = auth()->user();

    $user->setAvatarAttribute($request->file('avatar'));


    

    return response()->json([
        'message' => 'pic send'

        //'user' => $user,
        //'avatar' =>  $path ,
    ], 200);

}
public function saveavatar(Request $request)

{
    $user = auth()->user();

    if ($request->file('avatar')) {
        $avatar = $request->file('avatar');
        $avatarName = time() . '.' . $avatar->getClientOriginalExtension();
        $avatarPath = public_path('/avatar/');
        $avatar->move($avatarPath, $avatarName);
    
        if ($user) {
            $user->avatar = $avatarName;
            $user->save();
        }
    }
    



return response()->json([
    'message' => 'pic send',
    'message' => $avatarName,

    //'user' => $user,
    //'avatar' =>  $path ,
], 200);

}

public function showpic(User $user)
{
    $user = auth()->user();

        // Get the URL for the user's avatar image
        $avatarUrl = asset('avatar/' . $user->avatar);
    
        // Return the user and avatar URL as a JSON response
        return response()->json([
            'user' => $user,
            'avatar_url' => $avatarUrl,
        ]);
    
    
}

public function savebackground(Request $request)
{
    $user = auth()->user();

    if ($request->hasFile('background')) {
        $background = $request->file('background');
        $backgroundName = time() . '.' . $background->getClientOriginalExtension();
        $backgroundPath = public_path('/background/');
        $background->move($backgroundPath, $backgroundName);

        if ($user) {
            $user->background = $backgroundName;
            $user->save();
        }
    } else if ($request->has('background')) {
        $user->background = $request->input('background');
        $user->save();
    }
    $backgroundPath = public_path('background/' . $user->background);
    // Get the URL for the user's background image
    $backgroundUrl = asset('public/background/' . $user->background);

    return response()->json([
        'user' => $user,
        'background_url' => $backgroundUrl,


    ]);

    // If you want to redirect the user to the previous page with a success message, use the following code:
    // return back()->with('success', 'Background saved successfully');
}




}