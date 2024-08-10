<?php

namespace App\Providers;

use App\Http\Controllers\Interfaces\Dashboard\AdminServiceInterface;
use App\Http\Controllers\Interfaces\Dashboard\UserServiceInterface;
use App\Http\Controllers\Services\Dashboard\AdminService;
use App\Http\Controllers\Services\Dashboard\UserService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(AdminServiceInterface::class,AdminService::class);
        $this->app->bind(UserServiceInterface::class,UserService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
