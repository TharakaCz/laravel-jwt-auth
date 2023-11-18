<?php

namespace App\Http\Controllers;

use App\Models\User;
use Creativeorange\Gravatar\Facades\Gravatar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Ramsey\Uuid\Uuid;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function login(Request $request){
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        $credentials = $request->only('email', 'password');

        $token = Auth::guard('api')->attempt($credentials);
        if (!$token) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);
        }

        $user = Auth::guard('api')->user();
        return response()->json([
            'status' => 'success',
            'user' => $user,
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);

    }
    public function register(Request $request){

        $rules = Validator::make($request->all(), [
            "first_name" => ["required", "string"],
            "last_name" => ["required", "string"],
            "email" => ["required", "email", "unique:users,email"],
            "password" => ["required", "string", Password::min(8)->mixedCase()->symbols()],
        ]);

        if($rules->fails()){
            return $this->sendResponse(false, 403, $rules->errors()->all(), []);
//            throw new \HttpException($rules->errors(), 500);
        }

        $user = new User();
        $user->uuid = Uuid::uuid4();
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->avatar = isset($request->email) ? Gravatar::get($request->email, ['size'=>500]) : '';
        $user->avatar_type = isset($user->avatar)  ? User::$gravatar : '';
        $user->token = sha1(time() . $user->uuid . $user->email);

        try {

            $user->save();
            return $this->sendResponse(true, 200, 'Registration success', $user);

        }catch (\Exception $ex){
            return $this->sendResponse(false, 500, $ex->getMessage(), $user);
//            throw new HttpClientException($ex->getMessage(), 500);
        }

    }

    public function refresh(){
        return response()->json([
            'user' => Auth::user(),
            'authorisation' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
            ]
        ]);
    }

    public function logout(){
        Auth::logout();
        return response()->json([
            'message' => 'Successfully logged out',
        ]);
    }

    public function getAll(){
        try {
            $user = User::where(['is_active' => 1])->orderBy('created_at', 'desc')->get();
//            return $this->sendResponse(true, 200, "user list")
            return $user;
        }catch (\Exception $ex){
            return $this->sendResponse(false, 500, $ex->getMessage(), []);
        }
    }

    public function getList(){
        try {
            $user = User::where(['is_active' => 1])->orderBy('created_at', 'desc')->get();
            return $this->sendResponse(true, 200, "user list fetched.", $user);
        }catch (\Exception $ex){
            return $this->sendResponse(false, 500, $ex->getMessage(), []);
        }
    }
}
