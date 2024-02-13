<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Password;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Exception;

class AuthController extends Controller
{

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_name' => 'required|string',
            'password' => 'required|min:6',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }
        $credentials = $request->only('user_name', 'password');
        if (Auth::attempt($credentials))
        {

            $user = Auth::user();
            $user['token'] = $user->createToken('Laravelia')->accessToken;
            if($request->has('fcm_key'))
            {
                    $user->update(['fcm_key' => $request->input('fcm_key')]);
            }
         if($user->role=='child')
         {
            $data_show= User::select('users.*','childs.*')
            ->join('childs','childs.user_id','=','users.id')
            ->where('childs.user_id','=',$user->id)
            ->first();
                    $data_show['token']= $user['token'];
                    $data_show['child_id']=$data_show['id'];

                return response()->json([
                'messege'=> "Login Successfully",
                'user' => $data_show
            ], 200);
          }

        else if($user->role=='teacher')
        {
            $data_show= User::select('users.*','teachers.*')
                        ->join('teachers','teachers.user_id','=','users.id')
                        ->where('teachers.user_id','=',$user->id)
                        ->first();
                        $data_show['token']= $user['token'];
                        $data_show['teacher_id']=$data_show['id'];

                    return response()->json([
                    'messege'=> "Login Successfully",
                    'user' => $data_show
                ], 200);
        }

        }
        else{
            return response()->json([
                'messege'=> "wrong credintials",
            ], 401);

        }



    }



    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_name' => 'required|string|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $user = User::create([
            'user_name' => $request->user_name,
            'email' => $request->email,
            'password' => $request->password,
        ]);

        return response()->json([
            'messege'=> "registered Successfully",
        ], 200);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'messege'=> "logged out Successfully",
        ], 200);
    }

}
