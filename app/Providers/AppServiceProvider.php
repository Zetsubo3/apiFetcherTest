<?php

namespace App\Providers;

use App\Contracts\Repositories\OrderRepositoryInterface;
use App\Contracts\Repositories\SaleRepositoryInterface;
use App\Repositories\OrderRepository;
use App\Repositories\SaleRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            OrderRepositoryInterface::class,
            OrderRepository::class
        );

        $this->app->bind(
            SaleRepositoryInterface::class,
            SaleRepository::class,
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
