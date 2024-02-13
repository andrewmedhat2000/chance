<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Child;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Jobs\FCMNotification;

class EventController  extends Controller
{

     public function index(Request $request)
    {
        $events =Event::get();
        return response()->json([
            'status_code' => 200,
            'events' => $events,
        ]);
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'body' => 'required|string',
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
        $receivers;
        $event = Event::create($request->except('id'));
        if($request->id){
           $receivers=User::where('id', '=', $request->id)->pluck('id');
        }
        else{
            $receivers= User::pluck('id');
        }
        FCMNotification::dispatch(
            $receivers,
            $request->title,
            $request->body,
            [
                'type' => 'event',
                'sender_name' => 'Main Admin',
                'event_id' => $event->id,
                'body' =>$request->body,
            ],
            'EVENT'
        );


        return response()->json([
            'status_code' => 200,
            'message' => "event created successfully",
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
        $event=Event::where('id',$id)->first();
        if (! $event) {
         return response()->json([
             'status_code' => 404,
             'message' => 'event not found.'
         ], 404);
     }
     return response()->json([
        'status_code' => 200,
        'event_details' => $event,
     ]);
    }




    public function update(Request $request,  $id)
    {
        $validator = Validator::make($request->all(), [
            'body' => 'string',
            'title' => 'string',
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
        $event=Event::where('id',$id)->first();
        if (! $event) {
            return response()->json([
                'status_code' => 404,
                'message' => 'event not found.'
            ], 404);
        }
        $data = $request->all();

        $event->update($data);

        return response()->json([
            'status_code' => 200,
            'messege' => "event updated successfully",
        ]);
    }

    public function destroy($id)
    {
        $event = Event::find($id);
        if (! $event) {
            return response()->json([
                'status_code' => 404,
                'message' => 'event not found.'
            ], 404);
        }
        $event->delete();

        return response()->json([
            'status_code' => 200,
            'messege' => "event deleted successfully",
        ]);
    }
}
