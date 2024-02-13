<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Survey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class SurveyController  extends Controller
{

     public function index(Request $request)
    {
        $survey =Survey::where('department_id',$request->department_id)->first();
        if (! $survey) {
            return response()->json([
                'status_code' => 404,
                'message' => 'survey not found.'
            ], 404);
        }
        return response()->json([
            'status_code' => 200,
            'survey' => $survey,
        ]);
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'date' =>'required|date',
            'department_id' => 'required|unique:surveys,department_id|exists:departments,id',
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
        $survey = Survey::create($request->all());
        return response()->json([
            'status_code' => 200,
            'message' => "survey created successfully",
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
        $survey=Survey::where('id',$id)->first();
        if (! $survey) {
         return response()->json([
             'status_code' => 404,
             'message' => 'survey not found.'
         ], 404);
     }
     return response()->json([
        'status_code' => 200,
        'survey_details' => $survey,
     ]);
    }




    public function update(Request $request,  $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'string',
            'date' =>'date',
            'department_id' => 'unique:surveys,department_id,' . $id . '|exists:departments,id',
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
        $survey=Survey::where('id',$id)->first();
        if (! $survey) {
            return response()->json([
                'status_code' => 404,
                'message' => 'survey not found.'
            ], 404);
        }
        $data = $request->all();

        $survey->update($data);

        return response()->json([
            'status_code' => 200,
            'messege' => "survey updated successfully",
        ]);
    }

    public function destroy($id)
    {
        $survey = Survey::find($id);
        if (! $survey) {
            return response()->json([
                'status_code' => 404,
                'message' => 'survey not found.'
            ], 404);
        }
        $survey->delete();

        return response()->json([
            'status_code' => 200,
            'messege' => "survey deleted successfully",
        ]);
    }
}
