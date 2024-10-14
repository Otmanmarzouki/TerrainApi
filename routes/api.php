<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\TerrainController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\ClientsController;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

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

Route::post('/login', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
     
    ]);
 
    $user = User::where('email', $request->email)->first();
 
    if (! $user || ! Hash::check($request->password, $user->password)) {
        throw ValidationException::withMessages([
         'email' => ['The provided credentials are incorrect.'],
        ]);
    } 
   

    $Token = $user->createToken($request->email)->plainTextToken;
    $response =['user' => $user,'token' =>$Token];

    return response($response,201);
    
});


Route::post('/adduser',[UserController::class,'create']);

//----TerrainApis-----//

Route::post('/addTerrain',[TerrainController::class,'create']);
Route::get('/getTerrain',[TerrainController::class,'index']);
Route::get('/getTerrain/{id}', [TerrainController::class, 'getTerainById']);
Route::post('/update/{id}',[TerrainController::class,'update']);
Route::delete('/deleteTerrain', [TerrainController::class, 'destroy']);


//----ReservationApis-----//
Route::get('/getReservations',[ReservationController::class,'index']);
Route::post('/addReservation',[ReservationController::class,'create']);
Route::post('/updateReservation/{id}',[ReservationController::class,'update']);
Route::post('/deleteReservation/{id}',[ReservationController::class,'destroy']);
Route::post('/clients-count-by-sport', [ReservationController::class, 'getClientsCountBySport']);
//----ClientApis-----//
Route::get('/getClients',[ClientsController::class,'index']);
Route::get('/client/{id}', [ClientsController::class, 'findUniqueClient']);
Route::delete('/deleteClient', [ClientsController::class, 'destroy']);
Route::post('/clients/{id}/upload-logo', [ClientsController::class, 'uploadLogo']);