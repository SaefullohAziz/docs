<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

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
    	foreach ($data as $k => $v) {
    		$permission = Permission::create(['name' => $k, 'guard_name' => 'admin']);
    		foreach ($v as $name) {
                $permission->assignRole($name, 'admin');
    		}
    	}
    }
}