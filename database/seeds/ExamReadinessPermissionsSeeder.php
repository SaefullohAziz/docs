<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class ExamReadinessPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
    		'access exam_readinesses' => ['supersu', 'admin', 'finance', 'user'],
    		'create exam_readinesses' => ['supersu', 'admin'],
    		'read exam_readinesses' => ['supersu', 'admin', 'finance', 'user'],
    		'update exam_readinesses'=> ['supersu', 'admin'],
            'delete exam_readinesses' => ['supersu', 'admin'],
            'approval exam_readinesses'=> ['supersu', 'admin'],
    	];
    	foreach ($data as $k => $v) {
    		$permission = Permission::create(['name' => $k, 'guard_name' => 'admin']);
    		foreach ($v as $name) {
                $permission->assignRole($name, 'admin');
    		}
    	}
    }
}
