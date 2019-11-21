<?php

use Illuminate\Database\Seeder;

class TrainingSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		$trainingSettings = collect([
			[
				'name' => 'Basic (ToT)',
				'slug' => 'basic-tot',
				'status_slug' => 'basic_tot_status',
				'limiter_slug' => 'basic_tot_training_limiter',
				'time_limit_slug' => 'basic_tot_training_time_limit',
				'quota_limit_slug' => 'basic_tot_training_quota_limit',
				'school_level_slug' => 'basic_tot_training_school_level',
				'limit_by_level_slug' => 'basic_tot_training_limit_by_level',
				'school_implementation_slug' => 'basic_tot_training_school_implementation',
				'limit_by_implementation_slug' => 'basic_tot_training_limit_by_implementation',
				'default_participant_price_slug' => 'basic_tot_training_2_participant_price',
				'more_participant_slug' => 'basic_tot_training_more_participant',
				'unimplementation_scholl_price_slug' => 'basic_tot_training_unimplementation_school_price',
				'setting_created_at_slug' => 'basic_tot_training_setting_at',
				'status_slug_value' => 0,
				'limiter_slug_value' => 'None',
				'time_limit_slug_value' => '',
				'quota_limit_slug_value' => '',
				'school_level_slug_value' => [],
				'limit_by_level_slug_value' => [],
				'school_implementation_slug_value' => [],
				'limit_by_implementation_slug_value' => [],
				'default_participant_price_slug_value' => 3000000,
				'more_participant_slug_value' => '',
				'unimplementation_scholl_price_slug_value' => '',
			],
			[
				'name' => 'MikroTik',
				'slug' => 'mikrotik',
				'status_slug' => 'mikrotik_status',
				'status_slug_value' => 1,
				'limiter_slug' => 'mikrotik_training_limiter',
				'limiter_slug_value' => 'Quota',
				'time_limit_slug' => 'mikrotik_training_time_limit',
				'time_limit_slug_value' => '',
				'quota_limit_slug' => 'mikrotik_training_quota_limit',
				'quota_limit_slug_value' => 15,
				'school_level_slug' => 'mikrotik_training_school_level',
				'school_level_slug_value' => ['Binaan'],
				'limit_by_level_slug' => 'mikrotik_training_limit_by_level',
				'limit_by_level_slug_value' => ['Binaan' => 15],
				'school_implementation_slug' => 'mikrotik_training_school_implementation',
				'school_implementation_slug_value' => ['TKJ', 'Telin', 'TJAK'],
				'limit_by_implementation_slug' => 'mikrotik_training_limit_by_implementation',
				'limit_by_implementation_slug_value' => ['TKJ' => '', 'Telin' => '', 'TJAK' => ''],
				'default_participant_price_slug' => 'mikrotik_training_2_participant_price',
				'default_participant_price_slug_value' => 3000000,
				'more_participant_slug' => 'mikrotik_training_more_participant',
				'more_participant_slug_value' => 1850000,
				'unimplementation_scholl_price_slug' => 'mikrotik_training_unimplementation_school_price',
				'unimplementation_scholl_price_slug_value' => '-',
				'setting_created_at_slug' => 'mikrotik_training_setting_at'
			],
			[
				'name' => 'Seagate',
				'slug' => 'seagate',
				'status_slug' => 'seagate_status',
				'limiter_slug' => 'seagate_training_limiter',
				'time_limit_slug' => 'seagate_training_time_limit',
				'quota_limit_slug' => 'seagate_training_quota_limit',
				'school_level_slug' => 'seagate_training_school_level',
				'limit_by_level_slug' => 'seagate_training_limit_by_level',
				'school_implementation_slug' => 'seagate_training_school_implementation',
				'limit_by_implementation_slug' => 'seagate_training_limit_by_implementation',
				'default_participant_price_slug' => 'seagate_training_2_participant_price',
				'more_participant_slug' => 'seagate_training_more_participant',
				'unimplementation_scholl_price_slug' => 'seagate_training_unimplementation_school_price',
				'setting_created_at_slug' => 'seagate_training_setting_at',
				'status_slug_value' => 0,
				'limiter_slug_value' => 'None',
				'time_limit_slug_value' => '',
				'quota_limit_slug_value' => '',
				'school_level_slug_value' => [],
				'limit_by_level_slug_value' => [],
				'school_implementation_slug_value' => [],
				'limit_by_implementation_slug_value' => [],
				'default_participant_price_slug_value' => 3000000,
				'more_participant_slug_value' => '',
				'unimplementation_scholl_price_slug_value' => '',
			],
			[
				'name' => 'IoT',
				'slug' => 'iot',
				'status_slug' => 'iot_status',
				'limiter_slug' => 'iot_training_limiter',
				'time_limit_slug' => 'iot_training_time_limit',
				'quota_limit_slug' => 'iot_training_quota_limit',
				'school_level_slug' => 'iot_training_school_level',
				'limit_by_level_slug' => 'iot_training_limit_by_level',
				'school_implementation_slug' => 'iot_training_school_implementation',
				'limit_by_implementation_slug' => 'iot_training_limit_by_implementation',
				'default_participant_price_slug' => 'iot_training_2_participant_price',
				'more_participant_slug' => 'iot_training_more_participant',
				'unimplementation_scholl_price_slug' => 'iot_training_unimplementation_school_price',
				'setting_created_at_slug' => 'iot_training_setting_at',
				'status_slug_value' => 0,
				'limiter_slug_value' => 'None',
				'time_limit_slug_value' => '',
				'quota_limit_slug_value' => '',
				'school_level_slug_value' => [],
				'limit_by_level_slug_value' => [],
				'school_implementation_slug_value' => [],
				'limit_by_implementation_slug_value' => [],
				'default_participant_price_slug_value' => 3000000,
				'more_participant_slug_value' => '',
				'unimplementation_scholl_price_slug_value' => '',
			],
			[
				'name' => 'Dicoding',
				'slug' => 'dicoding',
				'status_slug' => 'dicoding_status',
				'limiter_slug' => 'dicoding_training_limiter',
				'time_limit_slug' => 'dicoding_training_time_limit',
				'quota_limit_slug' => 'dicoding_training_quota_limit',
				'school_level_slug' => 'dicoding_training_school_level',
				'limit_by_level_slug' => 'dicoding_training_limit_by_level',
				'school_implementation_slug' => 'dicoding_training_school_implementation',
				'limit_by_implementation_slug' => 'dicoding_training_limit_by_implementation',
				'default_participant_price_slug' => 'dicoding_training_2_participant_price',
				'more_participant_slug' => 'dicoding_training_more_participant',
				'unimplementation_scholl_price_slug' => 'dicoding_training_unimplementation_school_price',
				'setting_created_at_slug' => 'dicoding_training_setting_at',
				'status_slug_value' => 0,
				'limiter_slug_value' => 'None',
				'time_limit_slug_value' => '',
				'quota_limit_slug_value' => '',
				'school_level_slug_value' => [],
				'limit_by_level_slug_value' => [],
				'school_implementation_slug_value' => [],
				'limit_by_implementation_slug_value' => [],
				'default_participant_price_slug_value' => 3000000,
				'more_participant_slug_value' => '',
				'unimplementation_scholl_price_slug_value' => '',
			],
			[
				'name' => 'LS-Cable',
				'slug' => 'ls-cable',
				'status_slug' => 'ls_cable_status',
				'limiter_slug' => 'ls_cable_training_limiter',
				'time_limit_slug' => 'ls_cable_training_time_limit',
				'quota_limit_slug' => 'ls_cable_training_quota_limit',
				'school_level_slug' => 'ls_cable_training_school_level',
				'limit_by_level_slug' => 'ls_cable_training_limit_by_level',
				'school_implementation_slug' => 'ls_cable_training_school_implementation',
				'limit_by_implementation_slug' => 'ls_cable_training_limit_by_implementation',
				'default_participant_price_slug' => 'ls_cable_training_2_participant_price',
				'more_participant_slug' => 'ls_cable_training_more_participant',
				'unimplementation_scholl_price_slug' => 'ls_cable_training_unimplementation_school_price',
				'setting_created_at_slug' => 'ls_cable_training_setting_at',
				'status_slug_value' => 0,
				'limiter_slug_value' => 'None',
				'time_limit_slug_value' => '',
				'quota_limit_slug_value' => '',
				'school_level_slug_value' => [],
				'limit_by_level_slug_value' => [],
				'school_implementation_slug_value' => [],
				'limit_by_implementation_slug_value' => [],
				'default_participant_price_slug_value' => 3000000,
				'more_participant_slug_value' => '',
				'unimplementation_scholl_price_slug_value' => '',
			],
			[
				'name' => 'Surveillance',
				'slug' => 'surveillance',
				'status_slug' => 'surveillance_status',
				'limiter_slug' => 'surveillance_training_limiter',
				'time_limit_slug' => 'surveillance_training_time_limit',
				'quota_limit_slug' => 'surveillance_training_quota_limit',
				'school_level_slug' => 'surveillance_training_school_level',
				'limit_by_level_slug' => 'surveillance_training_limit_by_level',
				'school_implementation_slug' => 'surveillance_training_school_implementation',
				'limit_by_implementation_slug' => 'surveillance_training_limit_by_implementation',
				'default_participant_price_slug' => 'surveillance_training_2_participant_price',
				'more_participant_slug' => 'surveillance_training_more_participant',
				'unimplementation_scholl_price_slug' => 'surveillance_training_unimplementation_school_price',
				'setting_created_at_slug' => 'surveillance_training_setting_at',
				'status_slug_value' => 0,
				'limiter_slug_value' => 'None',
				'time_limit_slug_value' => '',
				'quota_limit_slug_value' => '',
				'school_level_slug_value' => [],
				'limit_by_level_slug_value' => [],
				'school_implementation_slug_value' => [],
				'limit_by_implementation_slug_value' => [],
				'default_participant_price_slug_value' => 3000000,
				'more_participant_slug_value' => '',
				'unimplementation_scholl_price_slug_value' => '',
			],
			[
				'name' => 'Elektronika Dasar',
				'slug' => 'elektronika-dasar',
				'status_slug' => 'elektronika_dasar_status',
				'limiter_slug' => 'elektronika_dasar_training_limiter',
				'time_limit_slug' => 'elektronika_dasar_training_time_limit',
				'quota_limit_slug' => 'elektronika_dasar_training_quota_limit',
				'school_level_slug' => 'elektronika_dasar_training_school_level',
				'limit_by_level_slug' => 'elektronika_dasar_training_limit_by_level',
				'school_implementation_slug' => 'elektronika_dasar_training_school_implementation',
				'limit_by_implementation_slug' => 'elektronika_dasar_training_limit_by_implementation',
				'default_participant_price_slug' => 'elektronika_dasar_training_2_participant_price',
				'more_participant_slug' => 'elektronika_dasar_training_more_participant',
				'unimplementation_scholl_price_slug' => 'elektronika_dasar_training_unimplementation_school_price',
				'setting_created_at_slug' => 'elektronika_dasar_training_setting_at',
				'status_slug_value' => 0,
				'limiter_slug_value' => 'None',
				'time_limit_slug_value' => '',
				'quota_limit_slug_value' => '',
				'school_level_slug_value' => [],
				'limit_by_level_slug_value' => [],
				'school_implementation_slug_value' => [],
				'limit_by_implementation_slug_value' => [],
				'default_participant_price_slug_value' => 3000000,
				'more_participant_slug_value' => '',
				'unimplementation_scholl_price_slug_value' => '',
			],
			[
				'name' => 'Adobe Photoshop',
				'slug' => 'adobe-photoshop',
				'status_slug' => 'adobe_photoshop_status',
				'limiter_slug' => 'adobe_photoshop_training_limiter',
				'time_limit_slug' => 'adobe_photoshop_training_time_limit',
				'quota_limit_slug' => 'adobe_photoshop_training_quota_limit',
				'school_level_slug' => 'adobe_photoshop_training_school_level',
				'limit_by_level_slug' => 'adobe_photoshop_training_limit_by_level',
				'school_implementation_slug' => 'adobe_photoshop_training_school_implementation',
				'limit_by_implementation_slug' => 'adobe_photoshop_training_limit_by_implementation',
				'default_participant_price_slug' => 'adobe_photoshop_training_2_participant_price',
				'more_participant_slug' => 'adobe_photoshop_training_more_participant',
				'unimplementation_scholl_price_slug' => 'adobe_photoshop_training_unimplementation_school_price',
				'setting_created_at_slug' => 'adobe_photoshop_training_setting_at',
				'status_slug_value' => 0,
				'limiter_slug_value' => 'None',
				'time_limit_slug_value' => '',
				'quota_limit_slug_value' => '',
				'school_level_slug_value' => [],
				'limit_by_level_slug_value' => [],
				'school_implementation_slug_value' => [],
				'limit_by_implementation_slug_value' => [],
				'default_participant_price_slug_value' => 3000000,
				'more_participant_slug_value' => '',
				'unimplementation_scholl_price_slug_value' => '',
			],
			[
				'name' => 'Microsoft Software Fundamental',
				'slug' => 'microsoft-software-fundamental',
				'status_slug' => 'microsoft_software_fundamental_status',
				'limiter_slug' => 'microsoft_software_fundamental_training_limiter',
				'time_limit_slug' => 'microsoft_software_fundamental_training_time_limit',
				'quota_limit_slug' => 'microsoft_software_fundamental_training_quota_limit',
				'school_level_slug' => 'microsoft_software_fundamental_training_school_level',
				'limit_by_level_slug' => 'microsoft_software_fundamental_training_limit_by_level',
				'school_implementation_slug' => 'microsoft_software_fundamental_training_school_implementation',
				'limit_by_implementation_slug' => 'microsoft_software_fundamental_training_limit_by_implementation',
				'default_participant_price_slug' => 'microsoft_software_fundamental_training_2_participant_price',
				'more_participant_slug' => 'microsoft_software_fundamental_training_more_participant',
				'unimplementation_scholl_price_slug' => 'microsoft_software_fundamental_training_unimplementation_school_price',
				'setting_created_at_slug' => 'microsoft_software_fundamental_training_setting_at',
				'status_slug_value' => 0,
				'limiter_slug_value' => 'None',
				'time_limit_slug_value' => '',
				'quota_limit_slug_value' => '',
				'school_level_slug_value' => [],
				'limit_by_level_slug_value' => [],
				'school_implementation_slug_value' => [],
				'limit_by_implementation_slug_value' => [],
				'default_participant_price_slug_value' => 3000000,
				'more_participant_slug_value' => '',
				'unimplementation_scholl_price_slug_value' => '',
			],
			[
				'name' => 'Starter Kit Klinik Komputer',
				'slug' => 'starter-kit-klinik-komputer',
				'status_slug' => 'starter_kit_klinik_komputer_status',
				'limiter_slug' => 'starter_kit_klinik_komputer_training_limiter',
				'time_limit_slug' => 'starter_kit_klinik_komputer_training_time_limit',
				'quota_limit_slug' => 'starter_kit_klinik_komputer_training_quota_limit',
				'school_level_slug' => 'starter_kit_klinik_komputer_training_school_level',
				'limit_by_level_slug' => 'starter_kit_klinik_komputer_training_limit_by_level',
				'school_implementation_slug' => 'starter_kit_klinik_komputer_training_school_implementation',
				'limit_by_implementation_slug' => 'starter_kit_klinik_komputer_training_limit_by_implementation',
				'default_participant_price_slug' => 'starter_kit_klinik_komputer_training_2_participant_price',
				'more_participant_slug' => 'starter_kit_klinik_komputer_training_more_participant',
				'unimplementation_scholl_price_slug' => 'starter_kit_klinik_komputer_training_unimplementation_school_price',
				'setting_created_at_slug' => 'starter_kit_klinik_komputer_training_setting_at',
				'status_slug_value' => 0,
				'limiter_slug_value' => 'None',
				'time_limit_slug_value' => '',
				'quota_limit_slug_value' => '',
				'school_level_slug_value' => [],
				'limit_by_level_slug_value' => [],
				'school_implementation_slug_value' => [],
				'limit_by_implementation_slug_value' => [],
				'default_participant_price_slug_value' => 3000000,
				'more_participant_slug_value' => '',
				'unimplementation_scholl_price_slug_value' => '',
			],
		]);
		setting(['training_settings' => $trainingSettings->toJson()])->save();
		foreach ($trainingSettings as $trainingSetting) {
			setting([
				$trainingSetting['status_slug'] => $trainingSetting['status_slug_value'],
				$trainingSetting['limiter_slug'] => $trainingSetting['limiter_slug_value'],
				$trainingSetting['time_limit_slug'] => $trainingSetting['time_limit_slug_value'],
				$trainingSetting['quota_limit_slug'] => $trainingSetting['quota_limit_slug_value'],
				$trainingSetting['school_level_slug'] => collect($trainingSetting['school_level_slug_value'])->toJson(),
				$trainingSetting['limit_by_level_slug'] => collect($trainingSetting['limit_by_level_slug_value'])->toJson(),
				$trainingSetting['school_implementation_slug'] => collect($trainingSetting['school_implementation_slug_value'])->toJson(),
				$trainingSetting['limit_by_implementation_slug'] => collect($trainingSetting['limit_by_implementation_slug_value'])->toJson(),
				$trainingSetting['default_participant_price_slug'] => $trainingSetting['default_participant_price_slug_value'],
				$trainingSetting['more_participant_slug'] => $trainingSetting['more_participant_slug_value'],
				$trainingSetting['unimplementation_scholl_price_slug'] => $trainingSetting['unimplementation_scholl_price_slug_value'],
				$trainingSetting['setting_created_at_slug'] => now()->toDateTimeString()
			])->save();
		}
    }
}