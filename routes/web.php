<?php

use App\Http\Controllers\Dashboard\AdminController;
use App\Http\Controllers\Dashboard\AuthController;
use App\Http\Controllers\Dashboard\HomeController;
use App\Http\Controllers\Dashboard\MatcheController;
use App\Http\Controllers\Dashboard\UserController;
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
            Route::get('logout','logout')->name('logout');
            Route::get('/',[HomeController::class,'index'])->name('index');

            Route::resource('admins',AdminController::class);
            Route::resource('users',UserController::class);
            Route::resource('matches',MatcheController::class);
    });


});
