<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EmailController;
use Illuminate\Foundation\Auth\EmailVerificationRequest; 
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

    Route::post('register', [AuthController::class, 'register']);
    Route::post('reset-password', [AuthController::class, 'resetPassword']);
    Route::post('forgot-password', [AuthController::class, 'createResetPasswordToken']);
});
Route::group(['middleware' => 'api', 'prefix' => 'profile'], function () {
    Route::get('', [ProfileController::class, 'index']);
    Route::post('add-profile', [ProfileController::class, 'create']);
    Route::put('update-profile', [ProfileController::class, 'store']);
    Route::delete('/delete-profile/{id}', [ProfileController::class, 'destroy']);
});


Route::prefix('email')->group(function () {
    Route::get('/verify/{id}/{hash}', [EmailController::class, 'checkVerifyEmail'])->middleware(['auth', 'signed'])->name('verification.verify');
    Route::get('/verification-notification', [EmailController::class, 'sendVerificationEmail'])->middleware(['auth', 'throttle:6,1'])->name('verification.send');
});