<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class QuestionController  extends Controller
{

     public function index(Request $request)
    {
        $questions =Question::where('survey_id',$request->survey_id)->get();
        if (! $questions) {
            return response()->json([
                'status_code' => 404,
                'message' => 'questions not found.'
            ], 404);
        }
        return response()->json([
            'status_code' => 200,
            'questions' => $questions,
        ]);
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'question_text' => 'required|string',
            'type' =>'required|in:trueOrfalse,multiChoose,MCQ,complete',
            'survey_id' => 'required|exists:surveys,id',
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
        $question = Question::create($request->all());
        return response()->json([
            'status_code' => 200,
            'message' => "question created successfully",
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
        $question=Question::where('id',$id)->first();
        if (! $question) {
         return response()->json([
             'status_code' => 404,
             'message' => 'question not found.'
         ], 404);
     }
     return response()->json([
        'status_code' => 200,
        'question_details' => $question,
     ]);
    }




    public function update(Request $request,  $id)
    {
        $validator = Validator::make($request->all(), [
            'question_text' => 'string',
            'type' =>'in:trueOrfalse,multiChoose,MCQ,complete',
            'survey_id' => 'exists:surveys,id',
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
        $question=Question::where('id',$id)->first();
        if (! $question) {
            return response()->json([
                'status_code' => 404,
                'message' => 'question not found.'
            ], 404);
        }
        $data = $request->all();

        $question->update($data);

        return response()->json([
            'status_code' => 200,
            'messege' => "question updated successfully",
        ]);
    }

    public function destroy($id)
    {
        $question = Question::find($id);
        if (! $question) {
            return response()->json([
                'status_code' => 404,
                'message' => 'question not found.'
            ], 404);
        }
        $question->delete();

        return response()->json([
            'status_code' => 200,
            'messege' => "question deleted successfully",
        ]);
    }
}
