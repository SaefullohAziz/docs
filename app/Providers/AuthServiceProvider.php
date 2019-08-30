<?php

namespace App\Providers;

use App\Student;
use App\Subsidy;
use App\Training;
use App\Payment;
use App\Policies\StudentPolicy;
use App\Policies\SubsidyPolicy;
use App\Policies\TrainingPolicy;
use App\Policies\PaymentPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Student::class => StudentPolicy::class,
        Subsidy::class => SubsidyPolicy::class,
        Training::class => TrainingPolicy::class,
        Payment::class => PaymentPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
