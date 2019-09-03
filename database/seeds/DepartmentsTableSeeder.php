<?php

use App\Department;
use Illuminate\Database\Seeder;

class DepartmentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['name' => 'Teknik Komputer dan Jaringan',
            'abbreviation' => 'TKJ',
            'description' => ''],

            ['name' => 'Rekayasa Perangkat Lunak',
            'abbreviation' => 'RPL',
            'description' => ''],

            ['name' => 'Multimedia',
            'abbreviation' => 'MM',
            'description' => ''],

            ['name' => 'Animasi',
            'abbreviation' => 'Animasi',
            'description' => ''],

            ['name' => 'Broadcasting',
            'abbreviation' => 'Broadcasting',
            'description' => ''],

            ['name' => 'Teknik Audio dan Video',
            'abbreviation' => 'TAV',
            'description' => ''],

            ['name' => 'Teknik Elektronika',
            'abbreviation' => 'TE',
            'description' => ''],

            ['name' => 'Teknik Elektronika dan Industri',
            'abbreviation' => 'TELIN',
            'description' => ''],

            ['name' => 'Teknik Sepeda Motor',
            'abbreviation' => 'TSM',
            'description' => ''],

            ['name' => 'Teknik Kendaraan Ringan',
            'abbreviation' => 'TKR',
            'description' => ''],

            ['name' => 'Teknik Gambar Bangunan',
            'abbreviation' => 'TGB',
            'description' => ''],

            ['name' => 'Administrasi Perkantoran',
            'abbreviation' => 'AP',
            'description' => ''],

            ['name' => 'Pemasaran',
            'abbreviation' => 'Pemasaran',
            'description' => ''],

            ['name' => 'Keuangan/Perbankan',
            'abbreviation' => 'Keuangan/Perbankan',
            'description' => ''],

            ['name' => 'Farmasi',
            'abbreviation' => 'Farmasi',
            'description' => ''],

            ['name' => 'Akuntansi',
            'abbreviation' => 'Akuntansi',
            'description' => ''],
        ];
        foreach ($data as $record) {
            Department::firstOrCreate($record);
        }
    }
}
