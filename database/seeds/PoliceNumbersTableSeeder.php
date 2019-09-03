<?php

use App\PoliceNumber;
use Illuminate\Database\Seeder;

class PoliceNumbersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['name' => 'A',
            'description' => ''],

            ['name' => 'AA', 
            'description' => ''],

            ['name' => 'AB', 
            'description' => ''],
            
            ['name' => 'AD', 
            'description' => ''],
            
            ['name' => 'AE', 
            'description' => ''],
            
            ['name' => 'AG', 
            'description' => ''],
            
            ['name' => 'B', 
            'description' => ''],
            
            ['name' => 'BA', 
            'description' => ''],
            
            ['name' => 'BB', 
            'description' => ''],
            
            ['name' => 'BD', 
            'description' => ''],
            
            ['name' => 'BE',
            'description' => ''],
            
            ['name' => 'BG', 
            'description' => ''],
            
            ['name' => 'BH', 
            'description' => ''],
            
            ['name' => 'BK', 
            'description' => ''],
            
            ['name' => 'BL', 
            'description' => ''],
            
            ['name' => 'BM', 
            'description' => ''],
            
            ['name' => 'BN', 
            'description' => ''],
            
            ['name' => 'BP', 
            'description' => ''],
            
            ['name' => 'D', 
            'description' => ''],
            
            ['name' => 'DA', 
            'description' => ''],
            
            ['name' => 'DB', 
            'description' => ''],
            
            ['name' => 'DC', 
            'description' => ''],
            
            ['name' => 'DD', 
            'description' => ''],
            
            ['name' => 'DE', 
            'description' => ''],
            
            ['name' => 'DF', 
            'description' => ''],
            
            ['name' => 'DG', 
            'description' => ''],
            
            ['name' => 'DH', 
            'description' => ''],
            
            ['name' => 'DK', 
            'description' => ''],
            
            ['name' => 'DL', 
            'description' => ''],
            
            ['name' => 'DM', 
            'description' => ''],
            
            ['name' => 'DN', 
            'description' => ''],
            
            ['name' => 'DP', 
            'description' => ''],
            
            ['name' => 'DR', 
            'description' => ''],
            
            ['name' => 'DS', 
            'description' => ''],
            
            ['name' => 'DT', 
            'description' => ''],
            
            ['name' => 'DW', 
            'description' => ''],
            
            ['name' => 'E', 
            'description' => ''],
            
            ['name' => 'EA', 
            'description' => ''],
            
            ['name' => 'EB', 
            'description' => ''],
            
            ['name' => 'ED', 
            'description' => ''],
            
            ['name' => 'F', 
            'description' => ''],
            
            ['name' => 'G', 
            'description' => ''],
            
            ['name' => 'H', 
            'description' => ''],
            
            ['name' => 'K', 
            'description' => ''],
            
            ['name' => 'KB', 
            'description' => ''],
            
            ['name' => 'KH', 
            'description' => ''],
            
            ['name' => 'KT', 
            'description' => ''],
            
            ['name' => 'KU', 
            'description' => ''],
            
            ['name' => 'L',
            'description' => ''],
            
            ['name' => 'M', 
            'description' => ''],
            
            ['name' => 'N', 
            'description' => ''],
            
            ['name' => 'P',
            'description' => ''],
            
            ['name' => 'PB', 
            'description' => ''],
            
            ['name' => 'R', 
            'description' => ''],
            
            ['name' => 'S', 
            'description' => ''],
            
            ['name' => 'T', 
            'description' => ''],
            
            ['name' => 'V',
            'description' => ''],
            
            ['name' => 'W', 
            'description' => ''],
            
            ['name' => 'X', 
            'description' => ''],
            
            ['name' => 'Z',
            'description' => ''],
        ];
        foreach ($data as $record) {
            PoliceNumber::firstOrCreate($record);
        }
    }
}
