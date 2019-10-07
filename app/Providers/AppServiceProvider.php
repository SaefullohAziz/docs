<?php

namespace App\Providers;

use App\Teacher;
use App\Student;
use App\Activity;
use App\Document;
use App\Subsidy;
use App\Training;
use App\ExamReadiness;
use App\Attendance;
use App\Payment;
use App\User;
use App\Observers\TeacherObserver;
use App\Observers\StudentObserver;
use App\Observers\ActivityObserver;
use App\Observers\DocumentObserver;
use App\Observers\SubsidyObserver;
use App\Observers\TrainingObserver;
use App\Observers\ExamReadinessObserver;
use App\Observers\AttendanceObserver;
use App\Observers\PaymentObserver;
use App\Observers\UserObserver;
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
        Teacher::observe(TeacherObserver::class);
        Student::observe(StudentObserver::class);
        Activity::observe(ActivityObserver::class);
        Document::observe(DocumentObserver::class);
        Subsidy::observe(SubsidyObserver::class);
        Training::observe(TrainingObserver::class);
        ExamReadiness::observe(ExamReadinessObserver::class);
        Attendance::observe(AttendanceObserver::class);
        Payment::observe(PaymentObserver::class);
        User::observe(UserObserver::class);
    }
}
