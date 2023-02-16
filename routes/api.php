<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\TerrainController;
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
   

    $Token = $user->createToken($request->password)->plainTextToken;
    $response =['user' => $user,'token' =>$Token];

    return response($response,201);
    
});


Route::post('/adduser',[UserController::class,'create']);

//----TerrainApis-----//

Route::post('/addTerrain',[TerrainController::class,'create']);
Route::get('/getTerrain',[TerrainController::class,'index']);
Route::post('/update/{id}',[TerrainController::class,'update']);
Route::post('/delete/{id}',[TerrainController::class,'destroy']);




