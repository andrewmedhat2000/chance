<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\Child;
use App\Models\Teacher;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Password;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Exception;

class ChildController extends Controller
{
    public function profile_details()
    {
        $user=Auth::user();
        if($user->role=='teacher'){
            $teacher = Teacher::with('user','departments')->where('user_id',$user->id)->first();
            if (!$teacher) {
                return response()->json([
                    'status_code'=> 404,
                    'message' => "teacher not found"
                ], 404);
            }
            return response()->json([
                'status_code'=> 200,
                'teacher' => $teacher
            ], 200);
        }

        else if($user->role=='child'){
         $child = Child::with('user')->where('user_id',$user->id)->first();

        if (!$child) {
            return response()->json([
                'status_code'=> 404,
                'message' => "child not found"
            ], 404);
                }

        return response()->json([
            'status_code'=> 200,
            'child' => $child
        ], 200);
        }
        }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
