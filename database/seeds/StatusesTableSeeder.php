<?php

use App\Status;
use Illuminate\Database\Seeder;

class StatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['name' => 'Created'],
            ['name' => 'Edited'],
            ['name' => 'Processed'],
            ['name' => 'Canceled'],
            ['name' => 'Approved'],
            ['name' => 'Payment'],
            ['name' => 'Paid'],
            ['name' => 'Participant'],
            ['name' => 'Waiting'],
            ['name' => 'Rejected'],
            ['name' => 'Sent'],
            ['name' => 'Rescheduled'],
            ['name' => 'Refunded'],
            ['name' => 'Expired'],
            ['name' => 'Published'],
        ];
        foreach ($data as $status) {
            Status::firstOrCreate($status);
        }
    }
}
