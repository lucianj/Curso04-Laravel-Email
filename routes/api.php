<?php

use App\Http\Controllers\Api\SeriesController;
use App\Models\Episode;
use App\Models\Series;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use \Illuminate\Support\Facades\Hash;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->group(function(){

    Route::apiResource('/series', SeriesController::class);
    Route::get('/series/{series}/seasons', 
        function (Series $series) {
            return $series->seasons;
        }
    );

    Route::get('/series/{series}/episodes',
        function (Series $series) {
            return $series->episodes;
        }
    );

    Route::patch('/episodes/{episode}', function(Episode $episode, Request $request){
        $episode->watched = $request->watched;
        $episode->save();

        return $episode;
    });
//Route::get('/series', [\App\Http\Controllers\Api\SeriesController::class, 'index']);
});

Route::post('/login', function(Request $request){
   $credentials = $request->only(['email', 'password']);
    //dd($credentials);
    //$user = \App\Models\User::whereEmail($credentials['email'])
    //    ->wherePassword(\Illuminate\Support\Facades\Hash::make($credentials['password']))
    //    ->first();
   
   // $user = \App\Models\User::whereEmail($credentials['email'])
    //    ->first();

   // if ($user === null || !Hash::make($credentials['password'], $user->password) === false){
   //     return response()->json("Unauthorized", 401);
   // }

   if(Auth::attempt($credentials) == false){
        return response()->json("Unauthorized", 401);
   }
   
   $user = Auth::user();
   $token = $user->createToken('token');

   return response()->json($token->plainTextToken);
   //dd($user);
});