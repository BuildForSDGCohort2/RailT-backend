<?php

namespace App\Http\Controllers;

use App\Station;
use App\Http\Resources\StationResource;
use Illuminate\Http\Request;

class StationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $stations = Station::with('staSchFroms', 'staSchTos')->get();
        return response(['station' => new StationResource($stations), 'message' => 'Stations Retrieved Success'], 200);
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
            's_state' => 'required',
            's_town' => 'required',
            's_number' => 'required',
            's_name' => 'required'
        ]);
        if ($data) {
            $station = Station::create($data);
            return response(['station' => new StationResource($station), 'message' => 'Station registered Successfully'], 200);
        }
        else {
            return response()->json(['errors' => $data->errors()->all()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Station  $station
     * @return \Illuminate\Http\Response
     */
    public function show(Station $station)
    {
        return response(['station' => new StationResource($station), 'message' => 'Retrieved Success'], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Station  $station
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Station $station)
    {
        $station->update($request->only('s_name', 's_number', 's_town', 's_state'));
        return response([ 'station' => new StationResource($station), 'message' => 'Updated successfully'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Station  $station
     * @return \Illuminate\Http\Response
     */
    public function destroy(Station $station)
    {
        $station->delete();
        return response()->json(['message' => null], 204);
    }
}
