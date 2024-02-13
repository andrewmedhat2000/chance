<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Models\Setter;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Models\PasswordReset;

class AuthController extends Controller
{


    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_name'         => 'required',
            'password'         => 'required'

        ]);

        if ($validator->fails()) {
            $est = $validator->messages();
            foreach ($est->all() as $key => $as) {
                $messages[] = $as;
            }
            return response()->json([
                'message' => $messages,
            ], 422);
          }
          $credentials = $request->only('user_name', 'password');
              //return $credentials;
            if (!auth()->attempt($credentials)) {

                return response()->json([
                    'status_code' => 422,
                    'message' => 'Wrong credentials',
                ]);
            }
          $user=User::where('user_name',$request->user_name)->where('role','admin')->first();
        if ($user) {
            $user['token'] = $user->createToken('Laravelia')->accessToken;

            return response()->json([
                'status_code' => 200,
                'user' => $user
            ], 200);

         }
         else{
            return response()->json([
                'status_code' => 404,
                'message' => "this user is not admin"
            ], 404);
         }

    }

}
