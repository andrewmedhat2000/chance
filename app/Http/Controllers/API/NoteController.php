<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\Note;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Password;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class NoteController extends Controller
{

 public function get_notes()
    {
        $user=Auth::user();
        $notes = Note::with('category')->where('user_id', $user->id)->get();
        if($notes){
            return response()->json([
                'status_code'=> 200,
                'notes' => $notes
            ], 200);
        }
        else{
            return response()->json([
                'status_code'=> 404,
                'message' => "there is no notes"
            ], 404);
        }

        return response()->json([
            'status_code'=> 200,
            'notes' => $notes
        ], 200);
     }
}
