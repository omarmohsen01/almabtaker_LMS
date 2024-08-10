<?php

use App\Http\Controllers\Front\Auth\AccessTokenController;
use App\Http\Controllers\Front\Auth\ForgetPasswordController;
use App\Http\Controllers\Front\MatcheController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(['middleware' => ['lang']], function () {
    // Public routes only
    Route::post('signin', [AccessTokenController::class, 'login']);
    Route::post('signup', [AccessTokenController::class, 'signup']);
    Route::get('verify-email/{user}/{token}', [AccessTokenController::class, 'verifyEmail'])
        ->name('verify-email');
    Route::post('/forget-password', [ForgetPasswordController::class, 'store']);
    Route::post('/forget-password/check-otp', [ForgetPasswordController::class, 'checkOtp']);
    Route::post('/forget-password/change-password', [ForgetPasswordController::class, 'changePassword']);
    /////////////////////////////zone///////////////////////////////////////

    /////////////////////////mtches/////////////////////////////////////////
    Route::get('/matches', [MatcheController::class, 'index']);
    Route::get('/matches/{id}', [MatcheController::class, 'show']);


});

Route::group(['middleware' => ['lang', 'auth:sanctum']], function () {
    Route::post('logout', [AccessTokenController::class, 'logout']);
    Route::get('my-account', [AccessTokenController::class, 'my_account']);

});
