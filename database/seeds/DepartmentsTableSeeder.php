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
            'abbreviation' => 'TKJ'],

            ['name' => 'Rekayasa Perangkat Lunak',
            'abbreviation' => 'RPL'],

            ['name' => 'Multimedia',
            'abbreviation' => 'MM'],

            ['name' => 'Animasi',
            'abbreviation' => 'Animasi'],

            ['name' => 'Broadcasting',
            'abbreviation' => 'Broadcasting'],

            ['name' => 'Teknik Audio dan Video',
            'abbreviation' => 'TAV'],

            ['name' => 'Teknik Elektronika',
            'abbreviation' => 'TE'],

            ['name' => 'Teknik Elektronika dan Industri',
            'abbreviation' => 'Telin'],

            ['name' => 'Teknik Sepeda Motor',
            'abbreviation' => 'TSM'],

            ['name' => 'Teknik Kendaraan Ringan',
            'abbreviation' => 'TKR'],

            ['name' => 'Teknik Gambar Bangunan',
            'abbreviation' => 'TGB'],

            ['name' => 'Administrasi Perkantoran',
            'abbreviation' => 'AP'],

            ['name' => 'Pemasaran',
            'abbreviation' => 'Pemasaran'],

            ['name' => 'Keuangan/Perbankan',
            'abbreviation' => 'Keuangan/Perbankan'],

            ['name' => 'Farmasi',
            'abbreviation' => 'Farmasi'],

            ['name' => 'Akuntansi',
            'abbreviation' => 'Akuntansi'],
        ];
        foreach ($data as $record) {
            Department::firstOrCreate($record);
        }
    }
}
