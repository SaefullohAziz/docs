<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RolesTableSeeder::class);
    	$this->call(AccountPermissionsTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(AreasTableSeeder::class);
        $this->call(SchoolStatusesTableSeeder::class);
        $this->call(SchoolPermissionsTableSeeder::class);
        $this->call(StudentPermissionsTableSeeder::class);
        $this->call(SubsidyPermissionsTableSeeder::class);
    }
}
