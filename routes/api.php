<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
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
Route::group(['middleware' => 'api', 'prefix' => 'auth'], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('me', [AuthController::class, 'me']);

    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
  
});
Route::group(['middleware'=>'api', 'prefix'=>'profile'], function () {
    Route::get('', [ProfileController::class, 'index']);
    Route::post('add-profile', [ProfileController::class, 'create']);
    Route::put('update-profile', [ProfileController::class, 'store']);
    Route::delete('/delete-profile/{id}', [ProfileController::class, 'destroy']);
});
Route::get('/register', [AuthController::class, 'register']);