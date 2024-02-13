<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\Teacher;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\TodayChild;
use Carbon\Carbon;

class TeacherController extends Controller
{
    public function my_departments()
    {
        $user = Auth::user();
        $teacher=Teacher::where('user_id',$user->id)->first();
        if (!$teacher) {
            return response()->json([
                'status_code'=> 404,
                'message' => "teacher not found"
            ], 404);
         }


        $departments = $teacher->departments;

        return response()->json([
            'status_code'=> 200,
            'departments' => $departments
        ], 200);
    }

    public function teacher_departments($id)
    {
        $teacher=Teacher::where('id',$id)->first();
        if (!$teacher) {
            return response()->json([
                'status_code'=> 404,
                'message' => "teacher not found"
            ], 404);
         }


        $departments = $teacher->departments;

        return response()->json([
            'status_code'=> 200,
            'departments' => $departments
        ], 200);
    }


    public function get_today_childs()
    {
        // Get today's date
        $today = Carbon::today();
        // Retrieve today's child records
        $user=Auth::user();
        $teacher=Teacher::where('user_id',$user->id)->first();
        if(! $teacher){
            return response()->json([
                'status_code'=> 404,
                'message' => "there is no teacher"
            ], 404);
        }
        $todayChilds = TodayChild::with('child.user')->where('teacher_id',$teacher->id)->whereDate('created_at', $today)->get();
        if( ! $todayChilds){
            $todayChilds = TodayChild::with('child.user')->where('teacher_id',$teacher->id)->get();
        }
        return response()->json([
            'status_code'=> 200,
            'todayChilds' => $todayChilds
        ], 200);
    }

}
