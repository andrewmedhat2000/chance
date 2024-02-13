<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class NoteController  extends Controller
{

     public function index(Request $request)
    {
        $notes =Note::with('category')->where('user_id',$request->user_id)->get();
        return response()->json([
            'status_code' => 200,
            'notes' => $notes,
        ]);
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'content' => 'required|string',
            'user_id' => 'required|exists:users,id',
            'category_id' => 'required|exists:notes_categories,id',
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
        $note = Note::create($request->all());
        return response()->json([
            'status_code' => 200,
            'message' => "note created successfully",
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
        $note=Note::with('category')->where('id',$id)->first();
        if (! $note) {
         return response()->json([
             'status_code' => 404,
             'message' => 'note not found.'
         ], 404);
     }
     return response()->json([
        'status_code' => 200,
        'note_details' => $note,
     ]);
    }




    public function update(Request $request,  $id)
    {
        $validator = Validator::make($request->all(), [
            'content' => 'string',
            'user_id' => 'exists:users,id',
            'category_id' => 'exists:notes_categories,id',
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
        $note=Note::where('id',$id)->first();
        if (! $note) {
            return response()->json([
                'status_code' => 404,
                'message' => 'note not found.'
            ], 404);
        }
        $data = $request->all();

        $note->update($data);

        return response()->json([
            'status_code' => 200,
            'messege' => "note updated successfully",
        ]);
    }

    public function destroy($id)
    {
        $note = Note::find($id);
        if (! $note) {
            return response()->json([
                'status_code' => 404,
                'message' => 'note not found.'
            ], 404);
        }
        $note->delete();

        return response()->json([
            'status_code' => 200,
            'messege' => "note deleted successfully",
        ]);
    }
}
