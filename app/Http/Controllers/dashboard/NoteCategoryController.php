<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\NoteCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class NoteCategoryController  extends Controller
{

     public function index(Request $request)
    {
        $note_category =NoteCategory::get();
        if (! $note_category) {
            return response()->json([
                'status_code' => 404,
                'message' => 'note category not found.'
            ], 404);
        }
        return response()->json([
            'status_code' => 200,
            'note_category' => $note_category,
        ]);
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
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
        $note_category = NoteCategory::create($request->all());
        return response()->json([
            'status_code' => 200,
            'message' => "note category created successfully",
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
        $note_category=NoteCategory::where('id',$id)->first();
        if (! $note_category) {
         return response()->json([
             'status_code' => 404,
             'message' => 'note category not found.'
         ], 404);
     }
     return response()->json([
        'status_code' => 200,
        'note_category' => $note_category,
     ]);
    }




    public function update(Request $request,  $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'string',
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
        $note_category=NoteCategory::where('id',$id)->first();
        if (! $note_category) {
            return response()->json([
                'status_code' => 404,
                'message' => 'note_category not found.'
            ], 404);
        }
        $data = $request->all();

        $note_category->update($data);

        return response()->json([
            'status_code' => 200,
            'messege' => "note_category updated successfully",
        ]);
    }

    public function destroy($id)
    {
        $note_category = NoteCategory::find($id);
        if (! $note_category) {
            return response()->json([
                'status_code' => 404,
                'message' => 'note_category not found.'
            ], 404);
        }
        $note_category->delete();

        return response()->json([
            'status_code' => 200,
            'messege' => "note_category deleted successfully",
        ]);
    }
}
