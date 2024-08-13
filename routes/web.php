<?php

use App\Http\Controllers\Dashboard\AdminController;
use App\Http\Controllers\Dashboard\AuthController;
use App\Http\Controllers\Dashboard\CategoryController;
use App\Http\Controllers\Dashboard\CountryController;
use App\Http\Controllers\Dashboard\HomeController;
use App\Http\Controllers\Dashboard\MatcheBookingController;
use App\Http\Controllers\Dashboard\MatcheController;
use App\Http\Controllers\Dashboard\PartyBookingController;
use App\Http\Controllers\Dashboard\PartyController;
use App\Http\Controllers\Dashboard\PromoCodeController;
use App\Http\Controllers\Dashboard\UserController;
use App\Http\Controllers\Dashboard\VisaBookingController;
use App\Http\Controllers\Dashboard\VisaController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::group(
    [
        'as' => 'dashboard.',
        'prefix' => 'acp/'
    ],function (){
        //routes for auth
        Route::controller(AuthController::class)->group(function (){
            Route::get('login', 'login_form')->name('login_form');
            Route::post('login', 'login')->name('login');
            Route::post('logout','logout')->name('logout');
        });

    Route::group(['middleware' => ['auth:admin']],function (){
        Route::get('/',[HomeController::class,'index'])->name('index');
        //route for profile ['index','update']
        Route::resource('admins',AdminController::class);
        Route::resource('users',UserController::class);
        ///////////////////matche/////////////////////////
        Route::resource('matches',MatcheController::class);
        Route::put('matche-booking/accept/{id}',[MatcheBookingController::class,'accept'])
            ->name('matches.accept');
        Route::put('matche-booking/reject/{id}',[MatcheBookingController::class,'reject'])
            ->name('matches.reject');
        Route::resource('matche-booking',MatcheBookingController::class);

        ////////////////////////visa//////////////////////
        Route::resource('visas', VisaController::class);
        Route::put('visa-booking/accept/{id}', [VisaBookingController::class, 'accept'])
        ->name('visa.accept');
        Route::put('visa-booking/reject/{id}', [visaBookingController::class, 'reject'])
        ->name('visa.reject');
        Route::resource('visa-booking', visaBookingController::class);

        ////////////////////////party//////////////////////
        Route::resource('parties', PartyController::class);
        Route::put('parties-booking/accept/{id}', [PartyBookingController::class, 'accept'])
        ->name('parties.accept');
        Route::put('parties-booking/reject/{id}', [PartyBookingController::class, 'reject'])
        ->name('parties.reject');
        Route::resource('parties-booking', visaBookingController::class);

        Route::resource('promo',PromoCodeController::class);
        Route::resource('categories',CategoryController::class);
        Route::resource('countries',CountryController::class);

    });
});
