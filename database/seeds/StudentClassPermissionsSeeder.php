<?php

use Illuminate\Database\Seeder;

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
            'close student_classes'=> ['supersu', 'admin'],
            'open student_classes'=> ['supersu', 'admin'],
    	];
    	foreach ($data as $k => $v) {
    		$permission = \Spatie\Permission\Models\Permission::firstOrCreate(['name' => $k, 'guard_name' => 'admin']);
    		$permission->syncRoles($v, 'admin');
    	}
    }
}
