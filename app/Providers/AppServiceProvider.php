<?php

namespace App\Providers;

use App\Invoice;
use App\Observers\InvoiceObserver;
use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Invoice::observe(InvoiceObserver::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Carbon::setLocale(config('app.locale'));
        setlocale(LC_TIME, config('app.locale'));
        setlocale(LC_MONETARY, config('app.money_locale'));
    }
}
