<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Department;
use App\Models\DepartmentTeacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class DepartmentController  extends Controller
{

     public function index(Request $request)
    {
        $departments = Department::get();
        if (! $departments) {
            return response()->json([
                'status_code' => 404,
                'message' => 'departments not found.'
            ], 404);
        }
        return response()->json([
            'status_code' => 200,
            'departments' => $departments,
        ]);
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:departments,name',
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
        $department = Department::create($request->all());
        return response()->json([
            'status_code' => 200,
            'message' => "department created successfully",
        ]);
    }

    public function department_teacher(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'teacher_id' => 'required|exists:teachers,id',
            'department_id' => 'required|exists:departments,id',
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
        $DepartmentTeacher = DepartmentTeacher::create($request->all());
        return response()->json([
            'status_code' => 200,
            'message' => "Department Teacher created successfully",
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
        $department=Department::where('id',$id)->first();
        if (! $department) {
         return response()->json([
             'status_code' => 404,
             'message' => 'department not found.'
         ], 404);
     }
     return response()->json([
        'status_code' => 200,
        'department_details' => $department,
     ]);
    }




    public function update(Request $request,  $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'string|unique:departments,name,' . $id,
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
        $department=Department::where('id',$id)->first();
        if (! $department) {
            return response()->json([
                'status_code' => 404,
                'message' => 'department not found.'
            ], 404);
        }
        $data = $request->all();

        $department->update($data);

        return response()->json([
            'status_code' => 200,
            'messege' => "department updated successfully",
        ]);
    }

    public function destroy($id)
    {
        $department = Department::find($id);
        if (! $department) {
            return response()->json([
                'status_code' => 404,
                'message' => 'department not found.'
            ], 404);
        }
        $department->delete();

        return response()->json([
            'status_code' => 200,
            'messege' => "department deleted successfully",
        ]);
    }
}
