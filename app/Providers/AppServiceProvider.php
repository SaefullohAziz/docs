<?php

namespace App\Providers;

use App\Subsidy;
use App\Training;
use App\Payment;
use App\Observers\SubsidyObserver;
use App\Observers\TrainingObserver;
use App\Observers\PaymentObserver;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

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
        Schema::defaultStringLength(191);
        Subsidy::observe(SubsidyObserver::class);
        Training::observe(TrainingObserver::class);
        Payment::observe(PaymentObserver::class);
    }
}
