<?php

namespace App\Http\Controllers;

use App\Carrier;
use App\Http\Resources\CarrierResource;
use Illuminate\Http\Request;

class CarrierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $carrier = Carrier::with('trainServiceProvider', 'cSchedules')->get();
        return response(['carrier' => new CarrierResource($carrier), 'message' => 'Carriers Retrieved Success'], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'c_name' => 'required',
            'c_capacity' => 'required',
            'c_owner' => 'required', //Foreign key
            'c_regNumber' => 'required'
        ]);
        if ($data) {
            $carrier = Carrier::create($data);
            return response(['carrier' => new CarrierResource($carrier), 'message' => 'Carrier registered Successfully'], 200);
        }
        else {
            return response()->json(['errors' => $data->errors()->all()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Carrier  $carrier
     * @return \Illuminate\Http\Response
     */
    public function show(Carrier $carrier)
    {
        return response(['carrier' => new CarrierResource($carrier), 'message' => 'Retrieved Success'], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Carrier  $carrier
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Carrier $carrier)
    {
        $carrier->update($request->only('c_name', 'c_regNumber', 'c_capacity', 'c_owner'));
        return response([ 'carrier' => new CarrierResource($carrier), 'message' => 'Updated successfully'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Carrier  $carrier
     * @return \Illuminate\Http\Response
     */
    public function destroy(Carrier $carrier)
    {
        $carrier->delete();
        return response()->json(['message' => null], 204);
    }
}
