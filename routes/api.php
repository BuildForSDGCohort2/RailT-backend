<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:api')->get('/user', function (Request $request) {
 //   return $request->user();
//});

Route::group(['prefix' => 'oauth'], function () {
    Route::get('/{driver}', 'Api\OauthController@redirectToProvider')->name('social.oauth');
    Route::get('/{driver}/callback', 'Api\OauthController@handleProviderCallback')->name('social.callback');
});

Route::post('register', 'UserController@register');
Route::post('login', 'UserController@login');
Route::post('logout', 'UserController@logout');
Route::apiResource('station', 'StationController');
Route::apiResource('trainServiceProvider', 'TrainServiceProviderController'); 
Route::apiResource('carrier', 'CarrierController'); 
Route::apiResource('ticket', 'TicketController'); 
Route::apiResource('schedule', 'ScheduleController'); 





