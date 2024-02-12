<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Yajra\DataTables\Html\Builder;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */

     // setting bootstrap as the styling method for the pagination
    public function boot(): void
    {
        Paginator::useBootstrapFive();
        Builder::useVite();
    }
}
