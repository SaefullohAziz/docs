<?php

use Illuminate\Database\Seeder;
use App\SchoolLevel;
use App\SchoolStatus;

class SchoolStatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $levels = [
        	[
        		'type' => 'Level',
        		'name' => 'Dalam proses',
        		'statuses' => [
        			[
        				'order_by' => '1a',
        				'name' => 'Daftar',
        				'alias' => 'Daftar',
        			],
					[
        				'order_by' => '1b',
        				'name' => 'Sedang diproses',
        				'alias' => 'Sedang diproses',
        			],
					[
        				'order_by' => '2a',
        				'name' => 'Sudah dapat pengantar visitasi',
        				'alias' => 'Sudah dapat pengantar visitasi',
        			],
					[
        				'order_by' => '2b',
        				'name' => 'Sudah visitasi',
        				'alias' => 'Sudah visitasi',
        			],
					[
        				'order_by' => '3a',
        				'name' => 'Sudah Dapat Jadwal Audiensi',
        				'alias' => 'Sudah Dapat Jadwal Audiensi',
        			],
					[
        				'order_by' => '3b',
        				'name' => 'Sudah Konfirmasi Hadir Audiensi',
        				'alias' => 'Sudah Konfirmasi Hadir Audiensi',
        			],
					[
        				'order_by' => '3c',
        				'name' => 'Sudah hadir audiensi',
        				'alias' => 'Sudah hadir audiensi',
        			],
					[
        				'order_by' => '4a',
        				'name' => 'Disetujui Level C',
        				'alias' => 'Menyetujui Bergabung',
        			],
					[
        				'order_by' => '4b',
        				'name' => 'Disetujui Potensi Level B',
        				'alias' => 'Menyetujui Bergabung',
        			],
					[
        				'order_by' => '4c',
        				'name' => 'Konfirmasi Persetujuan Sudah Dikirim',
        				'alias' => 'Konfirmasi Persetujuan Sudah Dikirim',
        			],
        		],
        	],
			[
        		'type' => 'Level',
        		'name' => 'C',
        		'statuses' => [
        			[
        				'order_by' => '5a',
        				'name' => 'Hardcopy CL Diterima',
        				'alias' => 'Hardcopy CL Diterima',
        			],
					[
        				'order_by' => '5b',
        				'name' => 'Sudah Daftar Basic Training',
        				'alias' => 'Sudah Daftar Basic Training',
        			],
					[
        				'order_by' => '5c',
        				'name' => 'Sudah Basic Training',
        				'alias' => 'Sudah Basic Training',
        			],
					[
        				'order_by' => '5d',
        				'name' => 'Implementasi Bertahap',
        				'alias' => 'Implementasi Bertahap',
        			],
        		],
        	],
			[
        		'type' => 'Level',
        		'name' => 'B',
        		'statuses' => [
        			[
        				'order_by' => '6a',
        				'name' => 'Waiting MoU',
        				'alias' => 'Waiting MoU',
        			],
					[
        				'order_by' => '6b',
        				'name' => 'Sudah ada siswa',
        				'alias' => 'Sudah ada siswa',
        			],
					[
        				'order_by' => '6c',
        				'name' => 'Sudah ada alumni',
        				'alias' => 'Sudah ada alumni',
        			],
        		],
        	],
			[
        		'type' => 'Level',
        		'name' => 'A',
        		'statuses' => [],
        	],
			[
        		'type' => 'Progress',
        		'name' => 'Follow Up',
        		'statuses' => [
        			[
        				'order_by' => '1d',
        				'name' => 'Tunda Sementara',
        				'alias' => 'Tunda Sementara',
        			],
					[
        				'order_by' => '1e',
        				'name' => 'Antrian Karena Geografis',
        				'alias' => 'Antrian Karena Geografis',
        			],
					[
        				'order_by' => '2c',
        				'name' => 'No Respond Visitasi',
        				'alias' => 'No Respond Visitasi',
        			],
					[
        				'order_by' => '3d',
        				'name' => 'No Respond Audiensi',
        				'alias' => 'No Respond Audiensi',
        			],
					[
        				'order_by' => '5e',
        				'name' => 'No Respond Basic Training',
        				'alias' => 'No Respond Basic Training',
        			],
					[
        				'order_by' => '5f',
        				'name' => 'No Respond CL',
        				'alias' => 'No Respond CL',
        			],
        		],
        	],
			[
        		'type' => 'Progress',
        		'name' => 'Proses Ulang',
        		'statuses' => [
        			[
        				'order_by' => '1e',
        				'name' => 'Sudah Bisa Daftar Ulang',
        				'alias' => 'Sudah Bisa Daftar Ulang',
        			],
					[
        				'order_by' => '1f',
        				'name' => 'Belum Disetujui',
        				'alias' => 'Belum Disetujui',
        			],
        		],
        	],
        ];

        foreach ($levels as $level) {
        	$schoolLevel = SchoolLevel::firstOrCreate([
        		'type' => $level['type'],
        		'name' => $level['name']
        	]);
        	foreach ($level['statuses'] as $status) {
        		$schoolLevel->status()->firstOrCreate([
        			'order_by' => $status['order_by'],
        			'name' => $status['name'],
        			'alias' => $status['alias']
        		]);
        	}
        }
    }
}
