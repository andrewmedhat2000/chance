<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Setter;
use App\Models\Parents;
use App\Models\Order;
use App\Models\Children;
use App\Models\Driver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class ParentController extends Controller
{


     public function index(Request $request)
     {
        $parents =Parents::with('user')->get();
        return response()->json([
            'status_code' => 200,
            'parents' => $parents,
        ]);
     }

     public function create(Request $request)
     {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone' => 'required|min:10|unique:users',
            'phone_code' => 'required|exists:countries,code',
            'image'=>'required|image',
            'national_id'=>'required|string|max:20|min:10',
            'address'=>'string',
            'nationality' => 'required|exists:countries,nationality',
            'long'          => 'required|numeric',
            'lat'          => 'required|numeric',
            'date_of_birth'          => 'required|date',
            'gender' =>
             ['required',
                 Rule::in(['male', 'female', 'ذكر', 'أنثى']),
            ],
            'email' => 'required|string|email|max:255|unique:users',
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

        $user = User::create($request->all());

        $parent = new Parents();
        $parent->long=$request->input('long');
        $parent->lat=$request->input('lat');
        $parent->user_id=$user->id;
        $parent->save();
        
        return response()->json([
            'status_code' => 200,
            'message' => "parent created successfully",
        ]);
     }


     public function show($id)
     {
        $parent=Parents::with('user')->where('id',$id)->first();
        if (! $parent) {
         return response()->json([
             'status_code' => 404,
             'message' => 'parent not found.'
         ], 404);
     }
        $data_show= User::select('users.*','parents.*')
         ->join('parents','parents.user_id','=','users.id')
         ->where('parents.id','=',$parent->id)
         ->first();
         return response()->json([
             'status_code' => 200,
             'parent_details' => $data_show,
         ]);

     }



     public function orders_details(Request $request)
      {
        $parent_id = $request->input('parent_id');
        $orders =Order::where('parent_id',$parent_id)->with('children')->get();
            return response()->json([
                'status_code' => 200,
                'orders' =>$orders,
            ]);
      }
      public function drivers_details(Request $request)
      {
        $parent_id = $request->input('parent_id');
        $drivers =Driver::where('parent_id',$parent_id)->get();
            return response()->json([
                'status_code' => 200,
                'drivers' =>$drivers,
            ]);
      }
      public function childrens_details(Request $request)
      {
        $parent_id = $request->input('parent_id');
        $childrens =Children::where('parent_id',$parent_id)->get();
            return response()->json([
                'status_code' => 200,
                'childrens' =>$childrens,
            ]);
      }
      public function user_details(Request $request)
      {
        $parent_id = $request->input('parent_id');
        $user= User::select('users.*')
            ->join('parents', 'users.id', '=', 'parents.user_id')
            ->where('parents.id', '=', $parent_id)
            ->first();
            return response()->json([
                'status_code' => 200,
                'user' =>$user,
            ]);
      }

     /**
      * Update the specified resource in storage.
      *
      * @param  \Illuminate\Http\Request  $request
      * @param  int  $id
      * @return \Illuminate\Http\Response
      */
     public function update(Request $request,$id)
     {
        $parent = Parents::find($id);
        if (! $parent) {
           return response()->json([
               'status_code' => 404,
               'message' => 'parent not found.'
           ], 404);
       }
         $user=User::find($parent->user_id);
         if (! $user) {
            return response()->json([
                'status_code' => 404,
                'message' => 'user not found.'
            ], 404);
        }
        $validator = Validator::make($request->all(), [
            'name' => 'string|max:255',
            'phone'          => 'unique:users,phone,' . $user->id,
            'phone_code' => 'exists:countries,code',
            'image'=>'image',
            'national_id'=>'string|max:20|min:10',
            'address'=>'string',
            'nationality' => 'exists:countries,nationality',
            'long'          => 'numeric',
            'lat'          => 'numeric',
            'date_of_birth'          => 'date',
            'gender' =>
             [
                 Rule::in(['male', 'female', 'ذكر', 'أنثى']),
            ],
            'email'    => 'email|unique:users,email,' . $user->id,
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
        $parent->update($request->all());

        $user->update($request->all());
         return response()->json([
            'status_code' => 200,
            'messege' => "parent updated successfully",
        ]);
     }

     public function destroy($id)
     {
         $parent = Parents::where('id', $id)->first();
         if (! $parent) {
            return response()->json([
                'status_code' => 404,
                'message' => 'parent not found.'
            ], 404);
        }
         $user = User::where('id', $parent->user_id)->first();
         $user->parent()->delete();
         $user->delete();
         return response()->json([
            'status_code' => 200,
            'messege' => "parent deleted successfully",
        ]);
     }
}
