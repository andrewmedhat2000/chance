<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Child;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Password;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Exception;

class UserController extends Controller
{
    public function updateProfile(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'date_of_birth' => 'required|date',
            'image' => 'required|image',
            'full_name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Update the user's profile information
        $user = Auth::user();
        $user->update($request->all());
        return response()->json([
            'status_code'=> 200,
            'message' => "Profile updated successfully"
        ], 200);
    }
    public function get_profile_by_id($id)
    {
        $child = Child::with('user.notes')->where('id',$id)->first();
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
