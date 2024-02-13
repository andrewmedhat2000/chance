<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Routing\Annotation\Route;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{


     public function index()
     {
        $users =User::where('role','admin')->get();
        return response()->json([
            'status_code' => 200,
            'users' => $users ,
        ]);
     }



     public function create(Request $request)
     {

        $validator = Validator::make($request->all(), [
            'user_name' => 'required|string|unique:users',
            'image'=>'required|image',
            'full_name'=>'string',
            'gallery'=>'string',
            'date_of_birth'          => 'date',
            'password' => 'required|string|min:8',
            'email' => 'required|string|email|max:255|unique:users',
            'role' => [
                'required',
                Rule::in(['admin']),
            ],
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

        $user = User::create($request->all());
        return response()->json([
            'status_code' => 200,
            'message' => "user created successfully",
        ]);
     }

     /**
      * Display the specified resource.
      *
      * @param  int  $id
      * @return \Illuminate\Http\Response
      */
     public function show($id)
     {
       $user=User::where('id',$id)->first();
       if (! $user) {
        return response()->json([
            'status_code' => 404,
            'message' => 'user not found.'
        ], 404);
    }

        return response()->json([
            'status_code' => 200,
            'user' => $user,
        ]);

     }

     /**
      * Show the form for editing the specified resource.
      *
      * @param  int  $id
      * @return \Illuminate\Http\Response
      */







     /**
      * Update the specified resource in storage.
      *
      * @param  \Illuminate\Http\Request  $request
      * @param  int  $id
      * @return \Illuminate\Http\Response
      */
     public function update(Request $request,$id)
     {
        $user = User::find($id);
        if (! $user) {
           return response()->json([
               'status_code' => 404,
               'message' => 'user not found.'
           ], 404);
       }
        
         $validator = Validator::make($request->all(), [
            'user_name'          => 'unique:users,user_name,' . $user->id,
            'image'=>'image',
            'full_name'=>'string',
            'gallery'=>'string',
            'date_of_birth'          => 'date',
            'password' => 'string|min:8',
            'role' => [
                Rule::in(['admin']),
            ],
            'email'    => 'email|unique:users,email,' . $user->id,
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
        $user->update($request->all());
         return response()->json([
            'status_code' => 200,
            'messege' => "user updated successfully",
        ]);
     }

     public function destroy($id)
     {
         $user = User::where('id', $id)->first();
         if (! $user) {
            return response()->json([
                'status_code' => 404,
                'message' => 'user not found.'
            ], 404);
        }
        
         $user->delete();
         return response()->json([
            'status_code' => 200,
            'messege' => "user deleted successfully",
        ]);
     }

}
