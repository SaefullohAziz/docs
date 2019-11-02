<?php

namespace App\Providers;

use App\School;
use App\Teacher;
use App\StudentClass;
use App\Student;
use App\Activity;
use App\Subsidy;
use App\Training;
use App\ExamReadiness;
use App\Attendance;
use App\Payment;
use App\Policies\SchoolPolicy;
use App\Policies\TeacherPolicy;
use App\Policies\StudentClassPolicy;
use App\Policies\StudentPolicy;
use App\Policies\ActivityPolicy;
use App\Policies\SubsidyPolicy;
use App\Policies\TrainingPolicy;
use App\Policies\ExamReadinessPolicy;
use App\Policies\AttendancePolicy;
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
        School::class => SchoolPolicy::class,
        Teacher::class => TeacherPolicy::class,
        StudentClass::class => StudentClassPolicy::class,
        Student::class => StudentPolicy::class,
        Activity::class => ActivityPolicy::class,
        Subsidy::class => SubsidyPolicy::class,
        Training::class => TrainingPolicy::class,
        ExamReadiness::class => ExamReadinessPolicy::class,
        Attendance::class => AttendancePolicy::class,
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

        // Implicitly grant "Super Admin" role all permissions
        // This works in the app by using gate-related functions like auth()->user->can() and @can()
        /*
        Gate::before(function ($user, $ability) {
            if (auth()->guard('admin')->check()) {
                return $user->hasRole('supersu') ? true : null;
            }
        });
        */

        if (setting('form_settings')) {
            foreach (json_decode(setting('form_settings')) as $formSetting) {
                Gate::define('create-' . str_replace(' ', '-', strtolower($formSetting->name)), function ($user) use ($formSetting) {
                    if (setting($formSetting->status_slug) == 1) {
                        $quota = \DB::table($formSetting->table)->where('created_at', '>=', setting($formSetting->setting_created_at_slug))->get()->count();
                        if (setting($formSetting->limiter_slug) == 'Quota') {
                            return setting($formSetting->quota_limit_slug) < $quota;
                        } elseif (setting($formSetting->limiter_slug) == 'Datetime') {
                            return date('Y-m-d h:m:s', strtotime(setting($formSetting->setting_created_at_slug))) <= date('Y-m-d h:m:s', strtotime(now()->toDateTimeString()));
                        } elseif (setting($formSetting->limiter_slug) == 'Both') {
                            if (setting($formSetting->quota_limit_slug) > $quota) {
                                return false;
                            } elseif (date('Y-m-d h:m:s', strtotime(setting($formSetting->setting_created_at_slug))) >= date('Y-m-d h:m:s', strtotime(now()->toDateTimeString()))) {
                                return false;
                            } 
                        }
                        return true;
                    }
                });
            }
        }
    }
}
