<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        // Activity
        'App\Events\ActivityApproved' => [
            'App\Listeners\ApproveActivity',
        ],
        // Subsidy
        'App\Events\SubsidyCanceled' => [
            'App\Listeners\CancelSubsidy',
        ],
        'App\Events\SubsidyRejected' => [
            'App\Listeners\RejectSubsidy',
        ],
        'App\Events\SubsidyApproved' => [
            'App\Listeners\ApproveSubsidy',
            'App\Listeners\CreateSubsidyPayment',
        ],
        // Training
        'App\Events\TrainingCanceled' => [
            'App\Listeners\CancelTraining',
        ],
        'App\Events\TrainingProcessed' => [
            'App\Listeners\ProcessTraining',
        ],
        'App\Events\TrainingApproved' => [
            'App\Listeners\CreateTrainingPayment',
            'App\Listeners\ApproveTraining',
        ],
        // Attendance
        'App\Events\AttendanceApproved' => [
            'App\Listeners\ApproveAttendance',
        ],
        'App\Events\AttendanceProcessed' => [
            'App\Listeners\ProcessAttendance',
        ],
        // Payment
        'App\Events\PaymentProcessed' => [
            'App\Listeners\ProcessPayment',
        ],
        'App\Events\PaymentApproved' => [
            'App\Listeners\ApprovePayment',
        ],
        'App\Events\PaymentSent' => [
            'App\Listeners\SendPayment',
        ],
        'App\Events\PaymentRefunded' => [
            'App\Listeners\RefundPayment',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
