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
            'slug' => 'mtcna',
            'description' => 'Mikrotik Certified Network Associate'],


            ['name' => 'IoT',
            'sub_name' => null,
            'slug' => 'iot',
            'description' => null],


            ['name' => 'Telview',
            'sub_name' => 'Surveillance Technology Fundamental (TCPS)',
            'slug' => 'telview_tcps',
            'description' => null],


            ['name' => 'Telview',
            'sub_name' => 'Surveillance Technology Junior Technician (TCT)',
            'slug' => 'telview_tct',
            'description' => null],


            ['name' => 'LS',
            'sub_name' => null,
            'slug' => 'ls_cable',
            'description' => null],

            ['name' => 'Microsoft',
            'sub_name' => 'MTA Network Fundamental (TKJ)',
            'slug' => 'mta_nf',
            'description' => null],


            ['name' => 'Microsoft',
            'sub_name' => 'MTA Software Fundamental (RPL)',
            'slug' => 'mta_sf',
            'description' => null],


            ['name' => 'Adobe',
            'sub_name' => 'Design Graphic Photoshop',
            'slug' => 'adobe',
            'description' => null],


            ['name' => 'Android Native (Dicoding)',
            'sub_name' => null,
            'slug' => 'native_android',
            'description' => 'Khusus sekolah RPL'],


            ['name' => 'AR (Vaganza)',
            'sub_name' => null,
            'slug' => 'ar_vaganza',
            'description' => 'Khusus sekolah Multimedia'],

            ['name' => 'Axioo',
            'sub_name' => 'ACP #1',
            'slug' => 'acp_1',
            'description' => null],


            ['name' => 'Axioo',
            'sub_name' => 'ACP #2',
            'slug' => 'acp_2',
            'description' => null],


            ['name' => 'Axioo',
            'sub_name' => 'ACP #3',
            'slug' => 'acp_3',
            'description' => null],

            ['name' => 'Remidial Axioo',
            'sub_name' => 'ACP #1',
            'slug' => 'remidial_acp_1',
            'description' => 'Ujian ini bagi siswa yang tidak lulus pada uji sertifikasi ACP sebelumnya'],


            ['name' => 'Remidial Axioo',
            'sub_name' => 'ACP #2',
            'slug' => 'remidial_acp_2',
            'description' => 'Ujian ini bagi siswa yang tidak lulus pada uji sertifikasi ACP sebelumnya'],


            ['name' => 'Remidial Axioo',
            'sub_name' => 'ACP #3',
            'slug' => 'remidial_acp_3',
            'description' => 'Ujian ini bagi siswa yang tidak lulus pada uji sertifikasi ACP sebelumnya'],


            ['name' => 'Network Operators',
            'sub_name' => null,
            'slug' => 'network_operator',
            'description' => null],
        ];
        foreach ($data as $insert) {
            \App\ExamType::updateOrCreate(
                [
                    'name' => $insert['name'],
                    'sub_name' => $insert['sub_name'],
                ],
                [
                    'slug' => $insert['slug'],
                    'description' => $insert['description'],
                ]
            );
        }
    }
}
