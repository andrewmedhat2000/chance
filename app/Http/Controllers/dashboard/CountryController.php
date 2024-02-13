<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Country;
use App\Models\Parents;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class CountryController  extends Controller
{


    public function index(Request $request)
    {
        $countries =Country::get();
        return response()->json([
            'status_code' => 200,
            'countries' => $countries,
        ]);
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code'=>'required|unique:countries,code',
            'name' => 'required|string|unique:countries,name',
            'nationality' => 'required|string|unique:countries,nationality',
            'code_iso'          => 'required|unique:countries,code_iso'
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

        $country = Country::create($request->all());
        return response()->json([
            'status_code' => 200,
            'message' => "country created successfully",
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

        $country=Country::where('id',$id)->first();
        if (! $country) {
         return response()->json([
             'status_code' => 404,
             'message' => 'country not found.'
         ], 404);
     }
     return response()->json([
        'status_code' => 200,
        'country_details' => $country,
    ]);
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,  $id)
    {
        $country=Country::where('id',$id)->first();

        if (! $country) {
            return response()->json([
                'status_code' => 404,
                'message' => 'country not found.'
            ], 404);
        }
        $validator = Validator::make($request->all(), [
            'code'          => 'unique:countries,code,' . $country->id,
            'nationality'          => 'unique:countries,nationality,' . $country->id,
            'name'          => 'unique:countries,name,' . $country->id,
            'code_iso'          => 'unique:countries,code_iso,' . $country->id,
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

        $data = $request->all();

        $country->update($data);

        return response()->json([
            'status_code' => 200,
            'messege' => "country updated successfully",
        ]);
    }

    public function destroy($id, Request $request)
    {
        $country = Country::find($id);
        if (! $country) {
            return response()->json([
                'status_code' => 404,
                'message' => 'country not found.'
            ], 404);
        }
        $country->delete();

        return response()->json([
            'status_code' => 200,
            'messege' => "country deleted successfully",
        ]);
    }

}
