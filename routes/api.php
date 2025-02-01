<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\TerrainController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\ClientsController;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;


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




//----UserApis-----//
Route::post('/login', [UserController::class, 'login']);
Route::post('/signup', [UserController::class, 'signUp']);
Route::middleware('auth:sanctum')->get('/user', [UserController::class, 'getUser']);

Route::middleware('auth:sanctum')->post('/user/modifier', [UserController::class, 'update']);
Route::delete('/user/delete/{id}', [UserController::class, 'destroy']);

//----TerrainApis-----//

Route::post('/terrain/ajouter', [TerrainController::class, 'create']);
Route::get('/terrains', [TerrainController::class, 'index']);
Route::get('/terrain/{id}', [TerrainController::class, 'getTerainById']);
Route::put('/terrain/modifier/{id}', [TerrainController::class, 'update']);
Route::delete('/terrain/delete/{id}', [TerrainController::class, 'destroy']);


//----ReservationApis-----//
Route::get('/getReservations', [ReservationController::class, 'index']);
Route::post('/addReservation', [ReservationController::class, 'store']);
Route::post('/updateReservation/{id}', [ReservationController::class, 'update']);
Route::post('/deleteReservation/{id}', [ReservationController::class, 'destroy']);
Route::post('/clients-count-by-sport', [ReservationController::class, 'getClientsCountBySport']);

Route::get('/drafts', [ReservationController::class, 'getDraftCount']);
//----ClientApis-----//
Route::get('/getClients', [ClientsController::class, 'index']);
Route::get('/client/{id}', [ClientsController::class, 'findUniqueClient']);
Route::delete('/deleteClient', [ClientsController::class, 'destroy']);
Route::post('/clients/{id}/upload-logo', [ClientsController::class, 'uploadLogo']);
//----historiqueApis-----//
Route::post('/check-availability', [ReservationController::class, 'getTerrainsWithReservations']);
