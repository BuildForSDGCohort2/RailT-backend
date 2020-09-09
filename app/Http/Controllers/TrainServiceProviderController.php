<?php

namespace App\Http\Controllers;

use App\TrainServiceProvider;
use App\Http\Resources\TrainServiceProviderResource;
use Illuminate\Http\Request;

class TrainServiceProviderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $trainServiceProviders = TrainServiceProvider::with('tspCarriers')->get();
        return response(['trainServiceProvider' => new TrainServiceProviderResource($trainServiceProviders), 'message' => 'TrainServiceProvider Retrieved Success'], 200);
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
            'name' => 'required',
            'about' => 'required',
            'address' => 'required',
        ]);
        if ($data) {
            $trainServiceProvider = TrainServiceProvider::create($data);
            return response(['trainServiceProvider' => new TrainServiceProviderResource($trainServiceProvider), 'message' => 'TrainServiceProvider registered Successfully'], 200);
        }
        else {
            return response()->json(['errors' => $data->errors()->all()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\TrainServiceProvider  $trainServiceProvider
     * @return \Illuminate\Http\Response
     */
    public function show(TrainServiceProvider $trainServiceProvider)
    {
        return response(['trainServiceProvider' => new TrainServiceProviderResource($trainServiceProvider), 'message' => 'Retrieved Success'], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\TrainServiceProvider  $trainServiceProvider
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TrainServiceProvider $trainServiceProvider)
    {
        $trainServiceProvider->update($request->only('name', 'about', 'address'));
        return response([ 'trainServiceProvider' => new TrainServiceProviderResource($trainServiceProvider), 'message' => 'Updated successfully'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\TrainServiceProvider  $trainServiceProvider
     * @return \Illuminate\Http\Response
     */
    public function destroy(TrainServiceProvider $trainServiceProvider)
    {
        $trainServiceProvider->delete();
        return response()->json(['message' => null], 204);
    }
}
