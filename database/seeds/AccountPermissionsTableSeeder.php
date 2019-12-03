<?php

use Illuminate\Database\Seeder;

class AccountPermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
    		'access accounts' => ['supersu', 'admin', 'finance', 'user'],
    		'create accounts' => ['supersu', 'admin'],
    		'read accounts' => ['supersu', 'admin'],
    		'update accounts'=> ['supersu', 'admin'],
    		'delete accounts' => ['supersu', 'admin']
    	];
        collect($data)->each(function ($item, $key) {
            $permission = \Spatie\Permission\Models\Permission::firstOrCreate(['name' => $key, 'guard_name' => 'admin']);
            $permission->syncRoles($item);
        });
    }
}