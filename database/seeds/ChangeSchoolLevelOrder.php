<?php

use Illuminate\Database\Seeder;

class ChangeSchoolLevelOrder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $levels = collect([
            'Dalam proses' => 1,
            'C' => 2,
            'B' => 3,
            'A' => 4,
            'Follow Up' => 5,
            'Proses Ulang' => 6,
        ]);
        $levels->each(function ($item, $key) {
            \App\SchoolLevel::where('name', $key)->update(['order' => $item]);
        });
    }
}
