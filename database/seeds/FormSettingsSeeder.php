<?php

use Illuminate\Database\Seeder;

class FormSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		$formSettings = collect([
			[
				'name' => 'School',
				'table' => 'schools',
				'status_slug' => 'school_form_status',
				'limiter_slug' => 'school_form_limiter',
				'time_limit_slug' => 'school_form_time_limit',
				'quota_limit_slug' => 'school_form_quota_limit',
				'setting_created_at_slug' => 'school_form_setting_at'
			],
			[
				'name' => 'Teacher',
				'table' => 'teachers',
				'status_slug' => 'teacher_form_status',
				'limiter_slug' => 'teacher_form_limiter',
				'time_limit_slug' => 'teacher_form_time_limit',
				'quota_limit_slug' => 'teacher_form_quota_limit',
				'setting_created_at_slug' => 'teacher_form_setting_at'
			],
			[
				'name' => 'Activity',
				'table' => 'activities',
				'status_slug' => 'activity_form_status',
				'limiter_slug' => 'activity_form_limiter',
				'time_limit_slug' => 'activity_form_time_limit',
				'quota_limit_slug' => 'activity_form_quota_limit',
				'setting_created_at_slug' => 'activity_form_setting_at'
			],
			[
				'name' => 'Subsidy',
				'table' => 'subsidies',
				'status_slug' => 'subsidy_form_status',
				'limiter_slug' => 'subsidy_form_limiter',
				'time_limit_slug' => 'subsidy_form_time_limit',
				'quota_limit_slug' => 'subsidy_form_quota_limit',
				'setting_created_at_slug' => 'subsidy_form_setting_at'
			],
			[
				'name' => 'Training',
				'table' => 'trainings',
				'status_slug' => 'training_form_status',
				'limiter_slug' => 'training_form_limiter',
				'time_limit_slug' => 'training_form_time_limit',
				'quota_limit_slug' => 'training_form_quota_limit',
				'setting_created_at_slug' => 'training_form_setting_at'
			],
			[
				'name' => 'Exam Readiness',
				'table' => 'exam_readinesses',
				'status_slug' => 'exam_readiness_form_status',
				'limiter_slug' => 'exam_readiness_form_limiter',
				'time_limit_slug' => 'exam_readiness_form_time_limit',
				'quota_limit_slug' => 'exam_readiness_form_quota_limit',
				'setting_created_at_slug' => 'exam_readiness_form_setting_at'
			],
			[
				'name' => 'Attendance',
				'table' => 'attendances',
				'status_slug' => 'attendance_form_status',
				'limiter_slug' => 'attendance_form_limiter',
				'time_limit_slug' => 'attendance_form_time_limit',
				'quota_limit_slug' => 'attendance_form_quota_limit',
				'setting_created_at_slug' => 'attendance_form_setting_at'
			],
			[
				'name' => 'Payment',
				'table' => 'payments',
				'status_slug' => 'payment_form_status',
				'limiter_slug' => 'payment_form_limiter',
				'time_limit_slug' => 'payment_form_time_limit',
				'quota_limit_slug' => 'payment_form_quota_limit',
				'setting_created_at_slug' => 'payment_form_setting_at'
			],
		]);
		setting(['form_settings' => $formSettings->toJson()])->save();
		foreach ($formSettings as $formSetting) {
			setting([$formSetting['status_slug'] => '1'])->save();
			setting([$formSetting['limiter_slug'] => 'None'])->save();
			setting([$formSetting['time_limit_slug'] => ''])->save();
			setting([$formSetting['quota_limit_slug'] => ''])->save();
			setting([$formSetting['setting_created_at_slug'] => now()->toDateTimeString()])->save();
		}
    }
}
