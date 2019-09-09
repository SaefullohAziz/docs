<?php

use Illuminate\Database\Seeder;

class ExamTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['name' => 'MTCNA',
            'sub_name' => '',
            'description' => 'Mikrotik Certified Network Associate'],


            ['name' => 'IoT',
            'sub_name' => '',
            'description' => ''],


            ['name' => 'Telview',
            'sub_name' => 'Surveillance Technology Fundamental (TCPS)',
            'description' => ''],


            ['name' => 'Telview',
            'sub_name' => 'Surveillance Technology Junior Technician (TCT)',
            'description' => ''],


            ['name' => 'LS',
            'sub_name' => '',
            'description' => ''],

            ['name' => 'Microsoft',
            'sub_name' => 'MTA Network Fundamental (TKJ)',
            'description' => ''],


            ['name' => 'Microsoft',
            'sub_name' => 'MTA Software Fundamental (RPL)',
            'description' => ''],


            ['name' => 'Adobe',
            'sub_name' => 'Design Graphic Photoshop',
            'description' => ''],


            ['name' => 'Android Native (Dicoding)',
            'sub_name' => 'Khusus sekolah RPL',
            'description' => ''],


            ['name' => 'AR (Vaganza)',
            'sub_name' => 'Khusus sekolah Multimedia',
            'description' => ''],

            ['name' => 'Axioo',
            'sub_name' => 'ACP #1',
            'description' => ''],


            ['name' => 'Axioo',
            'sub_name' => 'ACP #2',
            'description' => ''],


            ['name' => 'Axioo',
            'sub_name' => 'ACP #3',
            'description' => ''],

            ['name' => 'Remidial Axioo',
            'sub_name' => 'ACP #1',
            'description' => 'Ujian ini bagi siswa yang tidak lulus pada uji sertifikasi ACP sebelumnya'],


            ['name' => 'Remidial Axioo',
            'sub_name' => 'ACP #2',
            'description' => 'Ujian ini bagi siswa yang tidak lulus pada uji sertifikasi ACP sebelumnya'],


            ['name' => 'Remidial Axioo',
            'sub_name' => 'ACP #3',
            'description' => 'Ujian ini bagi siswa yang tidak lulus pada uji sertifikasi ACP sebelumnya'],


            ['name' => 'Network Operators',
            'sub_name' => '',
            'description' => ''],
        ];
        foreach ($data as $insert) {
            \App\ExamType::firstOrCreate($insert);
        }
    }
}
