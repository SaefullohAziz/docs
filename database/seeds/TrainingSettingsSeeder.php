<?php

use Illuminate\Database\Seeder;
use App\Department;

class TrainingSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		$department_names = Department::Pluck('abbreviation')->toArray();
		$departments = [];
		foreach ($department_names as $department) {
			$departments += [$department => 5];
		}
		$trainingSettings = collect([
			[
				'name' => 'basic_tot',
				'status_slug' => 'basic_tot_status',
				'limiter_slug' => 'basic_tot_limiter',
				'time_limit_slug' => 'basic_tot_time_limit',
				'quota_limit_slug' => 'basic_tot_quota_limit',
				'school_level_slug' => 'basic_tot_school_level',
				'limit_by_level_slug' => 'basic_tot_limit_by_level',
				'limit_by_level_binaan_slug' => 'basic_tot_limit_by_level_binaan',
				'school_implementation_slug' => 'basic_tot_school_implementation',
				'limit_by_implementation_slug' => 'basic_tot_limit_by_implementation',
				'default_participant_price_slug' => 'basic_tot_2_participant_price',
				'more_participant_slug' => 'basic_tot_more_participant',
				'unimplementation_scholl_price_slug' => 'basic_tot_unimplementation_school_price',
				'setting_created_at_slug' => 'basic_tot_setting_at'
			],
			[
				'name' => 'mikrotik',
				'status_slug' => 'mikrotik_status',
				'limiter_slug' => 'mikrotik_limiter',
				'time_limit_slug' => 'mikrotik_time_limit',
				'quota_limit_slug' => 'mikrotik_quota_limit',
				'school_level_slug' => 'mikrotik_school_level',
				'limit_by_level_slug' => 'mikrotik_limit_by_level',
				'limit_by_level_binaan_slug' => 'mikrotik_limit_by_level_binaan',
				'school_implementation_slug' => 'mikrotik_school_implementation',
				'limit_by_implementation_slug' => 'mikrotik_limit_by_implementation',
				'default_participant_price_slug' => 'mikrotik_2_participant_price',
				'more_participant_slug' => 'mikrotik_more_participant',
				'unimplementation_scholl_price_slug' => 'mikrotik_unimplementation_school_price',
				'setting_created_at_slug' => 'mikrotik_setting_at'
			],
			[
				'name' => 'seagate',
				'status_slug' => 'seagate_status',
				'limiter_slug' => 'seagate_limiter',
				'time_limit_slug' => 'seagate_time_limit',
				'quota_limit_slug' => 'seagate_quota_limit',
				'school_level_slug' => 'seagate_school_level',
				'limit_by_level_slug' => 'seagate_limit_by_level',
				'limit_by_level_binaan_slug' => 'seagate_limit_by_level_binaan',
				'school_implementation_slug' => 'seagate_school_implementation',
				'limit_by_implementation_slug' => 'seagate_limit_by_implementation',
				'default_participant_price_slug' => 'seagate_2_participant_price',
				'more_participant_slug' => 'seagate_more_participant',
				'unimplementation_scholl_price_slug' => 'seagate_unimplementation_school_price',
				'setting_created_at_slug' => 'seagate_setting_at'
			],
			[
				'name' => 'iot',
				'status_slug' => 'iot_status',
				'limiter_slug' => 'iot_limiter',
				'time_limit_slug' => 'iot_time_limit',
				'quota_limit_slug' => 'iot_quota_limit',
				'school_level_slug' => 'iot_school_level',
				'limit_by_level_slug' => 'iot_limit_by_level',
				'limit_by_level_binaan_slug' => 'iot_limit_by_level_binaan',
				'school_implementation_slug' => 'iot_school_implementation',
				'limit_by_implementation_slug' => 'iot_limit_by_implementation',
				'default_participant_price_slug' => 'iot_2_participant_price',
				'more_participant_slug' => 'iot_more_participant',
				'unimplementation_scholl_price_slug' => 'iot_unimplementation_school_price',
				'setting_created_at_slug' => 'iot_setting_at'
			],
			[
				'name' => 'dicoding',
				'status_slug' => 'dicoding_status',
				'limiter_slug' => 'dicoding_limiter',
				'time_limit_slug' => 'dicoding_time_limit',
				'quota_limit_slug' => 'dicoding_quota_limit',
				'school_level_slug' => 'dicoding_school_level',
				'limit_by_level_slug' => 'dicoding_limit_by_level',
				'limit_by_level_binaan_slug' => 'dicoding_limit_by_level_binaan',
				'school_implementation_slug' => 'dicoding_school_implementation',
				'limit_by_implementation_slug' => 'dicoding_limit_by_implementation',
				'default_participant_price_slug' => 'dicoding_2_participant_price',
				'more_participant_slug' => 'dicoding_more_participant',
				'unimplementation_scholl_price_slug' => 'dicoding_unimplementation_school_price',
				'setting_created_at_slug' => 'dicoding_setting_at'
			],
			[
				'name' => 'ls_cable',
				'status_slug' => 'ls_cable_status',
				'limiter_slug' => 'ls_cable_limiter',
				'time_limit_slug' => 'ls_cable_time_limit',
				'quota_limit_slug' => 'ls_cable_quota_limit',
				'school_level_slug' => 'ls_cable_school_level',
				'limit_by_level_slug' => 'ls_cable_limit_by_level',
				'limit_by_level_binaan_slug' => 'ls_cable_limit_by_level_binaan',
				'school_implementation_slug' => 'ls_cable_school_implementation',
				'limit_by_implementation_slug' => 'ls_cable_limit_by_implementation',
				'default_participant_price_slug' => 'ls_cable_2_participant_price',
				'more_participant_slug' => 'ls_cable_more_participant',
				'unimplementation_scholl_price_slug' => 'ls_cable_unimplementation_school_price',
				'setting_created_at_slug' => 'ls_cable_setting_at'
			],
			[
				'name' => 'surveillance',
				'status_slug' => 'surveillance_status',
				'limiter_slug' => 'surveillance_limiter',
				'time_limit_slug' => 'surveillance_time_limit',
				'quota_limit_slug' => 'surveillance_quota_limit',
				'school_level_slug' => 'surveillance_school_level',
				'limit_by_level_slug' => 'surveillance_limit_by_level',
				'limit_by_level_binaan_slug' => 'surveillance_limit_by_level_binaan',
				'school_implementation_slug' => 'surveillance_school_implementation',
				'limit_by_implementation_slug' => 'surveillance_limit_by_implementation',
				'default_participant_price_slug' => 'surveillance_2_participant_price',
				'more_participant_slug' => 'surveillance_more_participant',
				'unimplementation_scholl_price_slug' => 'surveillance_unimplementation_school_price',
				'setting_created_at_slug' => 'surveillance_setting_at'
			],
			[
				'name' => 'elektronika_dasar',
				'status_slug' => 'elektronika_dasar_status',
				'limiter_slug' => 'elektronika_dasar_limiter',
				'time_limit_slug' => 'elektronika_dasar_time_limit',
				'quota_limit_slug' => 'elektronika_dasar_quota_limit',
				'school_level_slug' => 'elektronika_dasar_school_level',
				'limit_by_level_slug' => 'elektronika_dasar_limit_by_level',
				'limit_by_level_binaan_slug' => 'elektronika_dasar_limit_by_level_binaan',
				'school_implementation_slug' => 'elektronika_dasar_school_implementation',
				'limit_by_implementation_slug' => 'elektronika_dasar_limit_by_implementation',
				'default_participant_price_slug' => 'elektronika_dasar_2_participant_price',
				'more_participant_slug' => 'elektronika_dasar_more_participant',
				'unimplementation_scholl_price_slug' => 'elektronika_dasar_unimplementation_school_price',
				'setting_created_at_slug' => 'elektronika_dasar_setting_at'
			],
			[
				'name' => 'adobe_photoshop',
				'status_slug' => 'adobe_photoshop_status',
				'limiter_slug' => 'adobe_photoshop_limiter',
				'time_limit_slug' => 'adobe_photoshop_time_limit',
				'quota_limit_slug' => 'adobe_photoshop_quota_limit',
				'school_level_slug' => 'adobe_photoshop_school_level',
				'limit_by_level_slug' => 'adobe_photoshop_limit_by_level',
				'limit_by_level_binaan_slug' => 'adobe_photoshop_limit_by_level_binaan',
				'school_implementation_slug' => 'adobe_photoshop_school_implementation',
				'limit_by_implementation_slug' => 'adobe_photoshop_limit_by_implementation',
				'default_participant_price_slug' => 'adobe_photoshop_2_participant_price',
				'more_participant_slug' => 'adobe_photoshop_more_participant',
				'unimplementation_scholl_price_slug' => 'adobe_photoshop_unimplementation_school_price',
				'setting_created_at_slug' => 'adobe_photoshop_setting_at'
			],
			[
				'name' => 'microsoft_software_fundamental',
				'status_slug' => 'microsoft_software_fundamental_status',
				'limiter_slug' => 'microsoft_software_fundamental_limiter',
				'time_limit_slug' => 'microsoft_software_fundamental_time_limit',
				'quota_limit_slug' => 'microsoft_software_fundamental_quota_limit',
				'school_level_slug' => 'microsoft_software_fundamental_school_level',
				'limit_by_level_slug' => 'microsoft_software_fundamental_limit_by_level',
				'limit_by_level_binaan_slug' => 'microsoft_software_fundamental_limit_by_level_binaan',
				'school_implementation_slug' => 'microsoft_software_fundamental_school_implementation',
				'limit_by_implementation_slug' => 'microsoft_software_fundamental_limit_by_implementation',
				'default_participant_price_slug' => 'microsoft_software_fundamental_2_participant_price',
				'more_participant_slug' => 'microsoft_software_fundamental_more_participant',
				'unimplementation_scholl_price_slug' => 'microsoft_software_fundamental_unimplementation_school_price',
				'setting_created_at_slug' => 'microsoft_software_fundamental_setting_at'
			],
			[
				'name' => 'starter_kit_klinik_komputer',
				'status_slug' => 'starter_kit_klinik_komputer_status',
				'limiter_slug' => 'starter_kit_klinik_komputer_limiter',
				'time_limit_slug' => 'starter_kit_klinik_komputer_time_limit',
				'quota_limit_slug' => 'starter_kit_klinik_komputer_quota_limit',
				'school_level_slug' => 'starter_kit_klinik_komputer_school_level',
				'limit_by_level_slug' => 'starter_kit_klinik_komputer_limit_by_level',
				'limit_by_level_binaan_slug' => 'starter_kit_klinik_komputer_limit_by_level_binaan',
				'school_implementation_slug' => 'starter_kit_klinik_komputer_school_implementation',
				'limit_by_implementation_slug' => 'starter_kit_klinik_komputer_limit_by_implementation',
				'default_participant_price_slug' => 'starter_kit_klinik_komputer_2_participant_price',
				'more_participant_slug' => 'starter_kit_klinik_komputer_more_participant',
				'unimplementation_scholl_price_slug' => 'starter_kit_klinik_komputer_unimplementation_school_price',
				'setting_created_at_slug' => 'starter_kit_klinik_komputer_setting_at'
			],
		]);
		setting(['training_settings' => $trainingSettings->toJson()])->save();
		foreach ($trainingSettings as $trainingSetting) {
			setting([$trainingSetting['status_slug'] => '1'])->save();
			setting([$trainingSetting['limiter_slug'] => 'None'])->save();
			setting([$trainingSetting['time_limit_slug'] => ''])->save();
			setting([$trainingSetting['quota_limit_slug'] => ''])->save();
			setting([$trainingSetting['school_level_slug'] => 'Binaan'])->save();
			setting([$trainingSetting['limit_by_level_slug'] => 'Binaan: 10'])->save();
			setting([$trainingSetting['limit_by_level_binaan_slug'] => '10'])->save();
			setting([$trainingSetting['school_implementation_slug'] => $department_names])->save();
			setting([$trainingSetting['limit_by_implementation_slug'] => $departments])->save();
			setting([$trainingSetting['default_participant_price_slug'] => '3000000'])->save();
			setting([$trainingSetting['more_participant_slug'] => '1500000'])->save();
			setting([$trainingSetting['unimplementation_scholl_price_slug'] => '5000000'])->save();
			setting([$trainingSetting['setting_created_at_slug'] => now()->toDateTimeString()])->save();
		}
    }
}