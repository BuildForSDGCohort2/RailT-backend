<?php

namespace App\Http\Controllers;

use App\Schedule;
use App\Http\Resources\ScheduleResource;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $schedules = Schedule::with('staFrom', 'staTo', 'tickets', 'schCarrier')->get();
        return response(['schedule' => new ScheduleResource($schedules), 'message' => 'List of Schedules Retrieved Success'], 200);
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
            'departure_time' => 'required',
            'arriva_time' => 'required',
            'from' => 'required',
            'to' => 'required',
            'carrier' => 'required'
        ]);
        if ($data) {
            $schedule = Schedule::create($data);
            return response(['schedule' => new ScheduleResource($schedule), 'message' => 'Schedule registered Successfully'], 200);
        }
        else {
            return response()->json(['errors' => $data->errors()->all()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Schedule  $schedule
     * @return \Illuminate\Http\Response
     */
    public function show(Schedule $schedule)
    {
        return response(['schedule' => new ScheduleResource($schedule), 'message' => 'Retrieved Success'], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Schedule  $schedule
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Schedule $schedule)
    {
        $schedule->update($request->only('departure_time', 'arriva_time', 'from', 'to', 'carrier'));
        return response([ 'schedule' => new ScheduleResource($schedule), 'message' => 'Updated successfully'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Schedule  $schedule
     * @return \Illuminate\Http\Response
     */
    public function destroy(Schedule $schedule)
    {
        $schedule->delete();
        return response()->json(['message' => null], 204);
    }
}
