<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class StudentPermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
    		'access students' => ['supersu', 'admin', 'finance', 'user'],
    		'create students' => ['supersu', 'admin'],
    		'read students' => ['supersu', 'admin', 'finance', 'user'],
    		'update students'=> ['supersu', 'admin'],
    		'delete students' => ['supersu', 'admin']
    	];
    	foreach ($data as $k => $v) {
    		$permission = Permission::create(['name' => $k, 'guard_name' => 'admin']);
    		foreach ($v as $name) {
                $permission->assignRole($name, 'admin');
    		}
    	}
    }
}
