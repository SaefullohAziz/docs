<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class StudentClassPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
    		'access student_classes' => ['supersu', 'admin', 'finance', 'user'],
    		'create student_classes' => ['supersu', 'admin'],
    		'read student_classes' => ['supersu', 'admin', 'finance', 'user'],
    		'update student_classes'=> ['supersu', 'admin'],
            'delete student_classes' => ['supersu', 'admin'],
            'approval student_classes'=> ['supersu', 'admin'],
    	];
    	foreach ($data as $k => $v) {
    		$permission = Permission::create(['name' => $k, 'guard_name' => 'admin']);
    		foreach ($v as $name) {
                $permission->assignRole($name, 'admin');
    		}
    	}
    }
}
