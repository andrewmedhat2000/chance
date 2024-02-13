<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\Survey;
use App\Models\Child;
use App\Models\AnsweredQuestion;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Password;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Exception;
use Carbon\Carbon;

class SurveyController extends Controller
{
 public function make_survey(Request $request)
{
    if($request->survey_type=='daily'){

        $survey = Survey::where('name','daily')
                    ->with('questions.answers')
                    ->first();

                    return response()->json([
                        'status_code'=> 200,
                        'survey' => $survey
                    ], 200);
        }
        else if($request->survey_type=='department'){
            $validator = Validator::make($request->all(), [
                'department_id' => 'required|exists:departments,id',
            ]);
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 422);
            }
            $survey = Survey::where('department_id',$request->department_id)
                        ->with('questions.answers')
                        ->first();

                        return response()->json([
                            'status_code'=> 200,
                            'survey' => $survey
                        ], 200);
            }

}
public function save_answer(Request $request)
{
    if($request->survey_type=='daily'){
    $validator = Validator::make($request->all(), [
        'survey_id' => 'required|exists:surveys,id',
        'child_id' => 'required|exists:childs,id',
        'questions.*.question' => 'required|string',
        'questions.*.answer' => 'required|string',
    ]);

    if ($validator->fails()) {
        return response()->json(['error' => $validator->errors()], 422);
    }

    $answers = [];
    $now = Carbon::now(); // Get the current timestamp
    foreach ($request->input('questions') as $question) {
        $answer = new AnsweredQuestion([
            'survey_id' => $request->input('survey_id'),
            'child_id' => $request->input('child_id'),
            'question' => $question['question'],
            'answer' => $question['answer'],
            'created' => $now,
        ]);
        $answers[] = $answer->toArray();
    }

    $AnsweredQuestions = AnsweredQuestion::insert($answers);
    return response()->json([
        'status_code' => 200,
        'message' => "submitted successfully"
    ], 200);
}
else if($request->survey_type=='department'){
    $validator = Validator::make($request->all(), [
        'survey_id' => 'required|exists:surveys,id',
        'child_id' => 'required|exists:childs,id',
        'teacher_id' => 'required|exists:teachers,id',
        'department_id' => 'required|exists:departments,id',
        'questions.*.question' => 'required|string',
        'questions.*.answer' => 'required|string',
    ]);

    if ($validator->fails()) {
        return response()->json(['error' => $validator->errors()], 422);
    }

    $answers = [];
    $now = Carbon::now(); // Get the current timestamp
    foreach ($request->input('questions') as $question) {
        $answer = new AnsweredQuestion([
            'survey_id' => $request->input('survey_id'),
            'child_id' => $request->input('child_id'),
            'teacher_id' => $request->input('teacher_id'),
            'department_id' => $request->input('department_id'),
            'question' => $question['question'],
            'answer' => $question['answer'],
            'created' => $now,
        ]);
        $answers[] = $answer->toArray();
    }

    $AnsweredQuestions = AnsweredQuestion::insert($answers);
    return response()->json([
        'status_code' => 200,
        'AnsweredQuestions' => $AnsweredQuestions
    ], 200);
}
}


public function get_child_report(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'child_id' => 'required|exists:childs,id',
            'date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }
        $questionsAndAnswers = AnsweredQuestion::where('child_id', $request->child_id)
            ->where('department_id', null)
            ->where('teacher_id', null)
            ->whereDate('created', $request->date)
            ->get(['question', 'answer']);
        if(!$questionsAndAnswers){

            return response()->json([
                'status_code' => 404,
                'message' => "nothing to show"
            ], 404);
        }
            return response()->json([
                'status_code' => 200,
                'questionsAndAnswers' => $questionsAndAnswers
            ], 200);
    }

    public function get_teacher_report(Request $request)
    {
        $user=Auth::user();
        $child=Child::where('user_id',$user->id)->first();
        $validator = Validator::make($request->all(), [
            'department_id' => 'required|exists:departments,id',
            'date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }
        $questionsAndAnswers = AnsweredQuestion::where('department_id', $request->department_id)
            ->where('child_id', $child->id)
            ->whereDate('created', $request->date)
            ->get(['question', 'answer']);
        if(!$questionsAndAnswers){

            return response()->json([
                'status_code' => 404,
                'message' => "nothing to show"
            ], 404);
        }
            return response()->json([
                'status_code' => 200,
                'questionsAndAnswers' => $questionsAndAnswers
            ], 200);
    }
}
