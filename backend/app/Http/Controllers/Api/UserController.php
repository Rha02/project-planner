<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function show()
    {
      $user = User::where('email', request('email'))->first();

      if (! $user) {
        return response()->json([
          'is_error' => true,
          'message' => 'This user does not exist!'
        ]);
      }

      return $user->toArray();
    }
}
