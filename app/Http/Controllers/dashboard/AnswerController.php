<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Answer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class AnswerController  extends Controller
{

     public function index(Request $request)
    {
        $answers = Answer::where('question_id',$request->question_id)->get();
        if (! $answers) {
            return response()->json([
                'status_code' => 404,
                'message' => 'answers not found.'
            ], 404);
        }
        return response()->json([
            'status_code' => 200,
            'answers' => $answers,
        ]);
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'answer_text' => 'required|string',
            'question_id' => 'required|exists:questions,id',
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
        $answer = Answer::create($request->all());
        return response()->json([
            'status_code' => 200,
            'message' => "answer created successfully",
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
        $answer=Answer::where('id',$id)->first();
        if (! $answer) {
         return response()->json([
             'status_code' => 404,
             'message' => 'answer not found.'
         ], 404);
     }
     return response()->json([
        'status_code' => 200,
        'answer_details' => $answer,
     ]);
    }




    public function update(Request $request,  $id)
    {
        $validator = Validator::make($request->all(), [
            'answer_text' => 'string',
            'question_id' => 'exists:questions,id',
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
        $answer=Answer::where('id',$id)->first();
        if (! $answer) {
            return response()->json([
                'status_code' => 404,
                'message' => 'answer not found.'
            ], 404);
        }
        $data = $request->all();

        $answer->update($data);

        return response()->json([
            'status_code' => 200,
            'messege' => "answer updated successfully",
        ]);
    }

    public function destroy($id)
    {
        $answer = Answer::find($id);
        if (! $answer) {
            return response()->json([
                'status_code' => 404,
                'message' => 'answer not found.'
            ], 404);
        }
        $answer->delete();

        return response()->json([
            'status_code' => 200,
            'messege' => "answer deleted successfully",
        ]);
    }
}
