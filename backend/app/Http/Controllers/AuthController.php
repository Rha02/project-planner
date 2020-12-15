<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class AuthController extends Controller
{
    public function __construct()
    {
      $this->middleware('auth:api')->except('login', 'register');
    }

    protected function respondWithToken($token)
    {
      return response()->json([
        'is_error' => false,
        'access_token' => $token,
        'token_type' => 'bearer',
        'expires_in' => auth()->factory()->getTTL() * 15
      ]);
    }

    public function login()
    {
      if (! $token = auth()->attempt(request(['email', 'password']))) {
        return response()->json([
          'is_error' => true,
          'error' => 'Unauthorized',
          'message' => 'The email or password you entered are incorrect. Check your credentials and try again.'
        ]);
      }

      return $this->respondWithToken($token);
    }

    public function logout()
    {
      auth()->logout();

      return response()->json([
        'status' => 'success',
        'msg' => 'User successfully logged out'
      ], 200);
    }

    public function refresh()
    {
      return $this->respondWithToken(auth()->refresh());
    }

    public function user()
    {
      return response()->json([
        'status' => 'success',
        'data' => [
          'email' => auth()->user()->email,
          'name' => auth()->user()->name
        ]
      ]);
    }

    public function register()
    {
      $validator = Validator::make(request()->all(), [
        'name' => 'required|min:3',
        'email' => 'required|email|unique:users',
        'password' => 'required|min:3|confirmed'
      ]);

      if ($validator->fails()) {
        return response()->json([
          'is_error' => true,
          'errors' => $validator->errors()
        ]);
      }

      $attributes = $validator->validated();

      $attributes['password'] = Hash::make($attributes['password']);

      $user = User::create($attributes);

      $token = auth()->login($user);

      return $this->respondWithToken($token);
    }
}
