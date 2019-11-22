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

        Gate::define('tes-euy', function ($user) {
            request()->session()->flash('status', 'Task was successful!');
            return true;
        });

        // Form Settings
        if (setting('form_settings')) {
            foreach (json_decode(setting('form_settings')) as $setting) {
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
            }
        }

        // Training Settings
        if (setting('training_settings')) {
            foreach (json_decode(setting('training_settings')) as $setting) {
                Gate::define('create-' . $setting->slug . '-training', function ($user) use ($setting) {
                    if (setting($setting->status_slug) == 1) {
                        if (collect(json_decode(setting($setting->school_level_slug)))->count()) {
                            $levels = collect([
                                'Dalam proses' => ['Dalam proses'],
                                'Rintisan' => ['C'],
                                'Binaan' => ['B', 'A']
                            ])->filter(function ($value, $key) use ($setting) {
                                return in_array($key, json_decode(setting($setting->school_level_slug), true));
                            })->flatten();
                            $levelLimitCount = collect(json_decode(setting($setting->limit_by_level_slug)))->filter(function ($value, $key) use ($user) {
                                $level = collect([
                                    'Dalam proses' => ['Dalam proses'],
                                    'Rintisan' => ['C'],
                                    'Binaan' => ['B', 'A']
                                ])->filter(function ($value, $key) use ($user) {
                                    return $user->hasLevel($value);
                                })->keys()->toArray();
                                return in_array($key, $level);
                            })->first();
                        }
                        if (collect(json_decode(setting($setting->school_implementation_slug)))->count()) {
                            $departments = collect(json_decode(setting($setting->school_implementation_slug)));
                            $implementatedDepartment = $departments->filter(function ($value, $key) {
                                return $value == request()->implementation;
                            })->first();
                            $departmentLimitCount = collect(json_decode(setting($setting->limit_by_implementation_slug)))->filter(function ($value, $key) use ($implementatedDepartment) {
                                return $key == $implementatedDepartment;
                            })->first();
                        }
                        if (setting($setting->limiter_slug) == 'Quota' || setting($setting->limiter_slug) == 'Both') {
                            $isLimitedTime = date('Y-m-d H:i:s', strtotime(setting($setting->setting_created_at_slug))) >= date('Y-m-d H:i:s', strtotime(now()->toDateTimeString()));
                        }
                        $quota = \App\TrainingParticipant::when( ! empty($levels), function ($training) use ($user, $levels) {
                            $training->whereHas('training.school.statusUpdate.status.level', function ($level) use ($user, $levels) {
                                $level->whereIn('name', $levels->toArray())
                                ->when($user->hasLevel($levels->toArray()), function ($subLevel) use ($user) {
                                    $subLevel->where('name', $user->school->statusUpdate->status->level->name);
                                });
                            });
                        })->when( ! empty($departments), function ($training) use ($departments, $implementatedDepartment) {
                            $training->whereHas('training.school.implementedDepartments', function ($department) use ($departments, $implementatedDepartment) {
                                $department->whereIn('abbreviation', $departments->toArray())
                                ->when($implementatedDepartment, function ($subDepartment) use ($implementatedDepartment) {
                                    $subDepartment->where('abbreviation', $implementatedDepartment);
                                });
                            });
                        })->whereDoesntHave('training.trainingStatus.status', function ($status) {
                            $status->where('name', 'Expired');
                        })->where('created_at', '>=', setting($setting->setting_created_at_slug));
                        $waitedQuota = $quota->has('training')->orderBy('created_at', 'asc')->get()->count();
                        $closestWatedQuota = $quota->where(function ($query) {
                            $query->has('training')->orWhereHas('training.payment.paymentStatus.status', function ($status) {
                                $status->where('name', '!=', 'Approved');
                            });
                        })->orderBy('created_at', 'asc')->limit(1)->first();
                        $quota = $quota->whereHas('training.payment.paymentStatus.status', function ($status) {
                            $status->where('name', 'Approved');
                        })->get()->count();
                        //
                        if (setting($setting->limiter_slug) == 'Quota') {
                            if ( ! empty($levels) || ! empty($departments)) {
                                if ( ! empty($levels) xor ! empty($departments)) {
                                    if ( ! empty($levels)) {
                                        if ( ! empty($levelLimitCount)) {
                                            request()->session()->flash('additionalMessage', __('Your school does not meet the requirements and / or quota already full.'));
                                            return $user->hasLevel($levels->toArray()) && $levelLimitCount > $quota;
                                        }
                                        request()->session()->flash('additionalMessage', __('Your school does not meet the requirements and / or quota already full.'));
                                        return $user->hasLevel($levels->toArray()) && setting($setting->quota_limit_slug) > $quota;
                                    } elseif ( ! empty($departments)) {
                                        if ( ! empty($departmentLimitCount)) {
                                            request()->session()->flash('additionalMessage', __('Your school does not meet the requirements and / or quota already full.'));
                                            return $implementatedDepartment && $departmentLimitCount > $quota;
                                        }
                                        request()->session()->flash('additionalMessage', __('Your school does not meet the requirements and / or quota already full.'));
                                        return $implementatedDepartment && setting($setting->quota_limit_slug) > $quota;
                                    }
                                }
                                request()->session()->flash('additionalMessage', __('Your school does not meet the requirements and / or quota already full.'));
                                // return ($user->hasLevel($levels->toArray()) && $implementatedDepartment && setting($setting->quota_limit_slug) > $quota) ? setting($setting->quota_limit_slug) > $waitedQuota : false;
                                return $user->hasLevel($levels->toArray()) && $implementatedDepartment && setting($setting->quota_limit_slug) > $quota;
                            }
                            request()->session()->flash('additionalMessage', __('Quota is full.'));
                            return setting($setting->quota_limit_slug) > $quota;
                        } elseif (setting($setting->limiter_slug) == 'Datetime') {
                            if ( ! empty($levels) || ! empty($departments)) {
                                request()->session()->flash('additionalMessage', __('Your school does not meet the requirements and / or registration time is up.'));
                                return ($user->hasLevel($levels->toArray()) || $implementatedDepartment) && $isLimitedTime;
                            }
                            request()->session()->flash('additionalMessage', __('Registration time is up.'));
                            return $isLimitedTime;
                        } elseif (setting($setting->limiter_slug) == 'Both') {
                            if ( ! empty($levels) || ! empty($departments)) {
                                if ( ! empty($levels) xor ! empty($departments)) {
                                    if ( ! empty($levels)) {
                                        if ( ! empty($levelLimitCount)) {
                                            return ($user->hasLevel($levels->toArray()) && $levelLimitCount > $quota) ? true : $isLimitedTime;
                                        }
                                        return ($user->hasLevel($levels->toArray()) && setting($setting->quota_limit_slug) > $quota) ? true : $isLimitedTime;
                                    } elseif ( ! empty($departments)) {
                                        if ( ! empty($departmentLimitCount)) {
                                            return ($implementatedDepartment && $departmentLimitCount > $quota) ? true : $isLimitedTime;
                                        }
                                        return ($implementatedDepartment && setting($setting->quota_limit_slug) > $quota) ? true : $isLimitedTime;
                                    }
                                }
                                return ($user->hasLevel($levels->toArray()) && $implementatedDepartment && setting($setting->quota_limit_slug) > $quota) ? true : $isLimitedTime;
                            }
                            return setting($setting->quota_limit_slug) > $quota ? true : $isLimitedTime;
                        }
                        return true;
                    }
                });
            }
        }
    }
}
