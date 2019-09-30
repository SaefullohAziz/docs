<?php

namespace App\Providers;

use App\Activity;
use App\Document;
use App\Subsidy;
use App\Training;
use App\ExamReadiness;
use App\Payment;
use App\Observers\ActivityObserver;
use App\Observers\DocumentObserver;
use App\Observers\SubsidyObserver;
use App\Observers\TrainingObserver;
use App\Observers\ExamReadinessObserver;
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
        Activity::observe(ActivityObserver::class);
        Document::observe(DocumentObserver::class);
        Subsidy::observe(SubsidyObserver::class);
        Training::observe(TrainingObserver::class);
        ExamReadiness::observe(ExamReadinessObserver::class);
        Payment::observe(PaymentObserver::class);
    }
}
