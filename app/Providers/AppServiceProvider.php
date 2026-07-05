<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\PayrollServiceInterface;
use App\Services\PayrollService;
class AppServiceProvider extends ServiceProvider
{

    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            PayrollServiceInterface::class,
            PayrollService::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
