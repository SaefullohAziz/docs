<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::firstOrCreate(['guard_name' => 'admin', 'name' => 'supersu']);
        Role::firstOrCreate(['guard_name' => 'admin', 'name' => 'admin']);
        Role::firstOrCreate(['guard_name' => 'admin', 'name' => 'finance']);
        Role::firstOrCreate(['guard_name' => 'admin', 'name' => 'user']);
    }
}
