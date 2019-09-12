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
            'sub_name' => null,
            'description' => 'Mikrotik Certified Network Associate'],


            ['name' => 'IoT',
            'sub_name' => null,
            'description' => null],


            ['name' => 'Telview',
            'sub_name' => 'Surveillance Technology Fundamental (TCPS)',
            'description' => null],


            ['name' => 'Telview',
            'sub_name' => 'Surveillance Technology Junior Technician (TCT)',
            'description' => null],


            ['name' => 'LS',
            'sub_name' => null,
            'description' => null],

            ['name' => 'Microsoft',
            'sub_name' => 'MTA Network Fundamental (TKJ)',
            'description' => null],


            ['name' => 'Microsoft',
            'sub_name' => 'MTA Software Fundamental (RPL)',
            'description' => null],


            ['name' => 'Adobe',
            'sub_name' => 'Design Graphic Photoshop',
            'description' => null],


            ['name' => 'Android Native (Dicoding)',
            'sub_name' => null,
            'description' => 'Khusus sekolah RPL'],


            ['name' => 'AR (Vaganza)',
            'sub_name' => null,
            'description' => 'Khusus sekolah Multimedia'],

            ['name' => 'Axioo',
            'sub_name' => 'ACP #1',
            'description' => null],


            ['name' => 'Axioo',
            'sub_name' => 'ACP #2',
            'description' => null],


            ['name' => 'Axioo',
            'sub_name' => 'ACP #3',
            'description' => null],

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
            'sub_name' => null,
            'description' => null],
        ];
        foreach ($data as $insert) {
            \App\ExamType::firstOrCreate($insert);
        }
    }
}
