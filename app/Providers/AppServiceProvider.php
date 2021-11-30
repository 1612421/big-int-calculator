<?php

namespace App\Providers;

use App\Services\CalculatorService;
use App\Services\Interfaces\ICalculatorService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(ICalculatorService::class, CalculatorService::class);
    }
}
