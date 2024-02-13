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

class TeacherController extends Controller
{


     public function index()
     {
        $teachers =Teacher::with('user')->get();
        return response()->json([
            'status_code' => 200,
            'teachers' => $teachers ,
        ]);
     }



     public function create(Request $request)
     {

        $validator = Validator::make($request->all(), [
            'user_name' => 'required|string|unique:users',
            'image'=>'required|image',
            'full_name'=>'string',
            'gallery'=>'string',
            'department_id' => 'required|exists:departments,id',
            'date_of_birth'          => 'date',
            'password' => 'required|string|min:8',
            'email' => 'required|string|email|max:255|unique:users',
            'role' => [
                'required',
                Rule::in(['teacher']),
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

        $user = User::create($request->except('department_id'));
        $teacher = new Teacher();
        $teacher->department_id = $request->department_id;
        $user->teacher()->save($teacher);
        $teacher->save();
        return response()->json([
            'status_code' => 200,
            'message' => "teacher created successfully",
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
       $teacher=Teacher::with('user')->where('id',$id)->first();
       if (! $teacher) {
        return response()->json([
            'status_code' => 404,
            'message' => 'teacher not found.'
        ], 404);
    }

        return response()->json([
            'status_code' => 200,
            'teacher' => $teacher,
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
        $teacher = Teacher::find($id);
        if (! $teacher) {
           return response()->json([
               'status_code' => 404,
               'message' => 'teacher not found.'
           ], 404);
       }
         $user=User::find($teacher->user_id);
         if (! $user) {
            return response()->json([
                'status_code' => 404,
                'message' => 'teacher not found.'
            ], 404);
        }
         $validator = Validator::make($request->all(), [
            'user_name'          => 'unique:users,user_name,' . $user->id,
            'image'=>'image',
            'full_name'=>'string',
            'gallery'=>'string',
            'department_id' => 'exists:departments,id',
            'date_of_birth'          => 'date',
            'password' => 'string|min:8',
            'role' => [
                Rule::in(['teacher']),
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
        $user->update($request->except('department_id'));
        $teacher->department_id = $request->department_id;
        $teacher->save();
         return response()->json([
            'status_code' => 200,
            'messege' => "teacher updated successfully",
        ]);
     }

     public function destroy($id)
     {
         $teacher = Teacher::where('id', $id)->first();
         if (! $teacher) {
            return response()->json([
                'status_code' => 404,
                'message' => 'teacher not found.'
            ], 404);
        }
         $user = User::where('id', $teacher->user_id)->first();
         $user->teacher()->delete();
         $user->delete();
         return response()->json([
            'status_code' => 200,
            'messege' => "teacher deleted successfully",
        ]);
     }

}
