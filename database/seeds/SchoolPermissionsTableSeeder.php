<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class SchoolPermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
    		'access schools' => ['supersu', 'admin', 'finance', 'user'],
    		'create schools' => ['supersu', 'admin'],
    		'read schools' => ['supersu', 'admin', 'finance', 'user'],
    		'update schools'=> ['supersu', 'admin'],
    		'delete schools' => ['supersu']
    	];
    	foreach ($data as $k => $v) {
    		$permission = Permission::create(['name' => $k, 'guard_name' => 'admin']);
    		foreach ($v as $name) {
                $permission->assignRole($name, 'admin');
    		}
    	}
    }
}
