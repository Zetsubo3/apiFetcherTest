<?php

namespace App\Providers;

use App\Contracts\Repositories\OrderRepositoryInterface;
use App\Contracts\Repositories\SaleRepositoryInterface;
use App\Contracts\Repositories\StockRepositoryInterface;
use App\Repositories\OrderRepository;
use App\Repositories\SaleRepository;
use App\Repositories\StockRepository;
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

        $this->app->bind(
            StockRepositoryInterface::class,
            StockRepository::class,
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
