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

        // Form Settings
        if (setting('form_settings')) {
            collect(json_decode(setting('form_settings')))->each(function ($setting) {
                Gate::define('create-' . str_replace(' ', '-', strtolower($setting->name)), function ($user) use ($setting) {
                    if (setting($setting->status_slug) == 1) {
                        $quota = \DB::table($setting->table)->where('created_at', '>=', setting($setting->setting_created_at_slug))->get()->count();
                        if (setting($setting->limiter_slug) == 'Quota') {
                            return setting($setting->quota_limit_slug) > $quota;
                        } elseif (setting($setting->limiter_slug) == 'Datetime') {
                            return date('Y-m-d H:i:s', strtotime(setting($setting->setting_created_at_slug))) >= date('Y-m-d H:i:s', strtotime(now()->toDateTimeString()));
                        } elseif (setting($setting->limiter_slug) == 'Both') {
                            if (setting($setting->quota_limit_slug) < $quota) {
                                return false;
                            } elseif (date('Y-m-d H:i:s', strtotime(setting($setting->setting_created_at_slug))) <= date('Y-m-d H:i:s', strtotime(now()->toDateTimeString()))) {
                                return false;
                            } 
                        }
                        return true;
                    }
                });
            });
        }

        // Training Settings
        if (setting('training_settings')) {
            collect(json_decode(setting('training_settings')))->each(function ($setting) {
                Gate::define('create-' . $setting->slug . '-training', function ($user) use ($setting) {
                    if (setting($setting->status_slug) == 1) {
                        $quotaSetting = \App\Training::quotaSetting($setting, $user);
                        if (setting($setting->limiter_slug) == 'Quota') {
                            if ( ! empty($quotaSetting['levels']) || ! empty($quotaSetting['departments'])) {
                                if ( ! empty($quotaSetting['levels']) xor ! empty($quotaSetting['departments'])) {
                                    if ( ! empty($quotaSetting['levels'])) {
                                        if ( ! empty($quotaSetting['levelLimitCount'])) {
                                            request()->session()->flash('additionalMessage', __('Your school does not meet the requirements and / or quota already full.') . ($quotaSetting['closestWaitedParticipant']?' ' . __('Please try again at ') . date('H:i:s', strtotime($quotaSetting['closestWaitedParticipant']->created_at . ' +3 hours')):''));
                                            return ($user->hasLevel($quotaSetting['levels']->toArray()) && $quotaSetting['levelLimitCount'] > $quotaSetting['quota']) ? $quotaSetting['levelLimitCount'] > $quotaSetting['waitedQuota'] : false;
                                        }
                                        request()->session()->flash('additionalMessage', __('Your school does not meet the requirements and / or quota already full.') . ($quotaSetting['closestWaitedParticipant']?' ' . __('Please try again at ') . date('H:i:s', strtotime($quotaSetting['closestWaitedParticipant']->created_at . ' +3 hours')):''));
                                        return ($user->hasLevel($quotaSetting['levels']->toArray()) && setting($setting->quota_limit_slug) > $quotaSetting['quota']) ? setting($setting->quota_limit_slug) > $quotaSetting['waitedQuota'] : false;
                                    } elseif ( ! empty($quotaSetting['departments'])) {
                                        if ( ! empty($quotaSetting['departmentLimitCount'])) {
                                            request()->session()->flash('additionalMessage', __('Your school does not meet the requirements and / or quota already full.') . ($quotaSetting['closestWaitedParticipant']?' ' . __('Please try again at ') . date('H:i:s', strtotime($quotaSetting['closestWaitedParticipant']->created_at . ' +3 hours')):''));
                                            return ($quotaSetting['implementedDepartment'] && $quotaSetting['departmentLimitCount'] > $quotaSetting['quota']) ? $quotaSetting['departmentLimitCount'] > $quotaSetting['waitedQuota'] : false;
                                        }
                                        request()->session()->flash('additionalMessage', __('Your school does not meet the requirements and / or quota already full.') . ($quotaSetting['closestWaitedParticipant']?' ' . __('Please try again at ') . date('H:i:s', strtotime($quotaSetting['closestWaitedParticipant']->created_at . ' +3 hours')):''));
                                        return ($quotaSetting['implementedDepartment'] && setting($setting->quota_limit_slug) > $quotaSetting['quota']) ? setting($setting->quota_limit_slug) > $quotaSetting['waitedQuota'] : false;
                                    }
                                }
                                request()->session()->flash('additionalMessage', __('Your school does not meet the requirements and / or quota already full.') . ($quotaSetting['closestWaitedParticipant']?' ' . __('Please try again at ') . date('H:i:s', strtotime($quotaSetting['closestWaitedParticipant']->created_at . ' +3 hours')):''));
                                return ($user->hasLevel($quotaSetting['levels']->toArray()) && $quotaSetting['implementedDepartment'] && setting($setting->quota_limit_slug) > $quotaSetting['quota'] && ($quotaSetting['levelLimitCount'] > $quotaSetting['quota'] || $quotaSetting['departmentLimitCount'] > $quotaSetting['quota'])) ? (setting($setting->quota_limit_slug) > $quotaSetting['waitedQuota'] && ($quotaSetting['levelLimitCount'] > $quotaSetting['waitedQuota'] || $quotaSetting['departmentLimitCount'] > $quotaSetting['waitedQuota'])) : false;
                            }
                            request()->session()->flash('additionalMessage', __('Quota is full.') . ($quotaSetting['closestWaitedParticipant']?' ' . __('Please try again at ') . date('H:i:s', strtotime($quotaSetting['closestWaitedParticipant']->created_at . ' +3 hours')):''));
                            return setting($setting->quota_limit_slug) > $quotaSetting['quota'] ? setting($setting->quota_limit_slug) > $quotaSetting['waitedQuota'] : false;
                        } elseif (setting($setting->limiter_slug) == 'Datetime') {
                            $isLimitedTime = date('Y-m-d H:i:s', strtotime(setting($setting->setting_created_at_slug))) >= date('Y-m-d H:i:s', strtotime(now()->toDateTimeString()));
                            if ( ! empty($quotaSetting['levels']) || ! empty($quotaSetting['departments'])) {
                                request()->session()->flash('additionalMessage', __('Your school does not meet the requirements and / or registration time is up.'));
                                return ($user->hasLevel($quotaSetting['levels']->toArray()) || $quotaSetting['implementedDepartment']) && $isLimitedTime;
                            }
                            request()->session()->flash('additionalMessage', __('Registration time is up.'));
                            return $isLimitedTime;
                        } elseif (setting($setting->limiter_slug) == 'Both') {
                            $isLimitedTime = date('Y-m-d H:i:s', strtotime(setting($setting->setting_created_at_slug))) >= date('Y-m-d H:i:s', strtotime(now()->toDateTimeString()));
                            if ( ! empty($quotaSetting['levels']) || ! empty($quotaSetting['departments'])) {
                                if ( ! empty($quotaSetting['levels']) xor ! empty($quotaSetting['departments'])) {
                                    if ( ! empty($quotaSetting['levels'])) {
                                        if ( ! empty($quotaSetting['levelLimitCount'])) {
                                            request()->session()->flash('additionalMessage', __('Your school does not meet the requirements and / or quota already full.') . ($quotaSetting['closestWaitedParticipant']?' ' . __('Please try again at ') . date('H:i:s', strtotime($quotaSetting['closestWaitedParticipant']->created_at . ' +3 hours')):''));
                                            return ($user->hasLevel($quotaSetting['levels']->toArray()) && $quotaSetting['levelLimitCount'] > $quotaSetting['quota']) ? ($quotaSetting['levelLimitCount'] > $quotaSetting['waitedQuota'] ? $isLimitedTime : false) : false;
                                        }
                                        request()->session()->flash('additionalMessage', __('Your school does not meet the requirements and / or quota already full.') . ($quotaSetting['closestWaitedParticipant']?' ' . __('Please try again at ') . date('H:i:s', strtotime($quotaSetting['closestWaitedParticipant']->created_at . ' +3 hours')):''));
                                        return ($user->hasLevel($quotaSetting['levels']->toArray()) && setting($setting->quota_limit_slug) > $quotaSetting['quota']) ? (setting($setting->quota_limit_slug) > $quotaSetting['waitedQuota'] ? $isLimitedTime : false) : false;
                                    } elseif ( ! empty($quotaSetting['departments'])) {
                                        if ( ! empty($quotaSetting['departmentLimitCount'])) {
                                            request()->session()->flash('additionalMessage', __('Your school does not meet the requirements and / or quota already full.') . ($quotaSetting['closestWaitedParticipant']?' ' . __('Please try again at ') . date('H:i:s', strtotime($quotaSetting['closestWaitedParticipant']->created_at . ' +3 hours')):''));
                                            return ($quotaSetting['implementedDepartment'] && $quotaSetting['departmentLimitCount'] > $quotaSetting['quota']) ? ($quotaSetting['departmentLimitCount'] > $quotaSetting['waitedQuota'] ? $isLimitedTime : false) : false;
                                        }
                                        request()->session()->flash('additionalMessage', __('Your school does not meet the requirements and / or quota already full.') . ($quotaSetting['closestWaitedParticipant']?' ' . __('Please try again at ') . date('H:i:s', strtotime($quotaSetting['closestWaitedParticipant']->created_at . ' +3 hours')):''));
                                        return ($quotaSetting['implementedDepartment'] && setting($setting->quota_limit_slug) > $quotaSetting['quota']) ? (setting($setting->quota_limit_slug) > $quotaSetting['waitedQuota'] ? $isLimitedTime : false) : false;
                                    }
                                }
                                request()->session()->flash('additionalMessage', __('Your school does not meet the requirements and / or quota already full.') . ($quotaSetting['closestWaitedParticipant']?' ' . __('Please try again at ') . date('H:i:s', strtotime($quotaSetting['closestWaitedParticipant']->created_at . ' +3 hours')):''));
                                return ($user->hasLevel($quotaSetting['levels']->toArray()) && $quotaSetting['implementedDepartment'] && setting($setting->quota_limit_slug) > $quotaSetting['quota'] && ($quotaSetting['levelLimitCount'] > $quotaSetting['quota'] || $quotaSetting['departmentLimitCount'] > $quotaSetting['quota'])) ? ((setting($setting->quota_limit_slug) > $quotaSetting['waitedQuota'] && ($quotaSetting['levelLimitCount'] > $quotaSetting['waitedQuota'] || $quotaSetting['departmentLimitCount'] > $quotaSetting['waitedQuota'])) ? $isLimitedTime : false) : false;
                            }
                            request()->session()->flash('additionalMessage', __('Your school does not meet the requirements and / or quota already full.') . ($quotaSetting['closestWaitedParticipant']?' ' . __('Please try again at ') . date('H:i:s', strtotime($quotaSetting['closestWaitedParticipant']->created_at . ' +3 hours')):''));
                            return setting($setting->quota_limit_slug) > $quotaSetting['quota'] ? (setting($setting->quota_limit_slug) > $quotaSetting['waitedQuota'] ? $isLimitedTime : false) : false;
                        }
                        return true;
                    }
                });
            });
        }
    }
}
