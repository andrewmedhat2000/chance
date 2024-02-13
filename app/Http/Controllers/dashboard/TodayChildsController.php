<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\TodayChild;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class TodayChildsController  extends Controller
{

     public function index(Request $request)
    {
        $TodayChilds =TodayChild::with('child.user')->where('teacher_id',$request->teacher_id)->get();
        if (! $TodayChilds) {
            return response()->json([
                'status_code' => 404,
                'message' => 'TodayChilds not found.'
            ], 404);
        }
        return response()->json([
            'status_code' => 200,
            'TodayChilds' => $TodayChilds,
        ]);
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'child_id' => 'required|exists:childs,id',
            'teacher_id' => 'required|exists:teachers,id',
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
        $TodayChild = TodayChild::create($request->all());
        return response()->json([
            'status_code' => 200,
            'message' => "TodayChild created successfully",
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
        $TodayChild=TodayChild::where('id',$id)->first();
        if (! $TodayChild) {
         return response()->json([
             'status_code' => 404,
             'message' => 'TodayChild not found.'
         ], 404);
     }
     return response()->json([
        'status_code' => 200,
        'TodayChild' => $TodayChild,
     ]);
    }




    public function update(Request $request,  $id)
    {
        $validator = Validator::make($request->all(), [
            'child_id' => 'required|exists:childs,id',
            'teacher_id' => 'required|exists:teachers,id',
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
        $TodayChild=TodayChild::where('id',$id)->first();
        if (! $TodayChild) {
            return response()->json([
                'status_code' => 404,
                'message' => 'TodayChild not found.'
            ], 404);
        }
        $data = $request->all();

        $TodayChild->update($data);

        return response()->json([
            'status_code' => 200,
            'messege' => "TodayChild updated successfully",
        ]);
    }

    public function destroy($id)
    {
        $TodayChild = TodayChild::find($id);
        if (! $TodayChild) {
            return response()->json([
                'status_code' => 404,
                'message' => 'TodayChild not found.'
            ], 404);
        }
        $TodayChild->delete();

        return response()->json([
            'status_code' => 200,
            'messege' => "TodayChild deleted successfully",
        ]);
    }
}
