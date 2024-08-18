<?php

use App\Http\Controllers\Front\Auth\AccessTokenController;
use App\Http\Controllers\Front\Auth\ForgetPasswordController;
use App\Http\Controllers\Front\MatcheController;
use App\Http\Controllers\Front\PartyController;
use App\Http\Controllers\Front\SettingController;
use App\Http\Controllers\Front\SliderController;
use App\Http\Controllers\Front\VisaController;
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
    Route::get('countries',[SettingController::class,'get_countries']);
    Route::get('categories',[SettingController::class,'get_categories']);
    /////////////////////////matches/////////////////////////////////////////
    Route::get('/matches', [MatcheController::class, 'index']);
    Route::get('/matches/{id}', [MatcheController::class, 'show']);
    /////////////////////////parties/////////////////////////////////////////
    Route::get('/parties', [PartyController::class, 'index']);
    Route::get('/parties/{id}', [PartyController::class, 'show']);
    /////////////////////////visa/////////////////////////////////////////
    Route::get('/visa', [VisaController::class, 'index']);
    Route::get('/visa/{id}', [VisaController::class, 'show']);

    Route::get('sliders',[SliderController::class,'index']);

});

Route::group(['middleware' => ['lang', 'auth:sanctum']], function () {
    Route::post('logout', [AccessTokenController::class, 'logout']);
    Route::get('my-account', [AccessTokenController::class, 'my_account']);
    //////////////////////matches///////////////////////////////////
    if (request()->header('Authorization')) {
        Route::get('/matches/{id}', [MatcheController::class, 'show']);
        Route::get('/matches', [MatcheController::class, 'index']);
        Route::post('/matches', [MatcheController::class, 'store']);
        /////////////////////////parties/////////////////////////////////////////
        Route::get('/parties', [PartyController::class, 'index']);
        Route::get('/parties/{id}', [PartyController::class, 'show']);
        Route::post('/parties', [PartyController::class, 'store']);
        /////////////////////////visa/////////////////////////////////////////
        Route::get('/visa', [VisaController::class, 'index']);
        Route::get('/visa/{id}', [VisaController::class, 'show']);
        Route::post('/visa', [VisaController::class, 'store']);

        Route::get('sliders', [SliderController::class, 'index']);
    }
    Route::get('promo-codes',[SettingController::class,'get_promo_code']);

});