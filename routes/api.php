<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
// use Illuminate\Auth\Events\EmailVerificationRequest;
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
    Route::post('reset-password', [AuthController::class, 'updatePassword']);
    Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
});
Route::group(['middleware' => 'api', 'prefix' => 'profile'], function () {
    Route::get('', [ProfileController::class, 'index']);
    Route::post('add-profile', [ProfileController::class, 'create']);
    Route::put('update-profile', [ProfileController::class, 'store']);
    Route::delete('/delete-profile/{id}', [ProfileController::class, 'destroy']);
});

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/home');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return back()->with('message', 'Verification link sent!');
});