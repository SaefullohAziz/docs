<?php

use Illuminate\Database\Seeder;

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
            'bin exam_readinesses' => ['supersu', 'admin'],
            'restore exam_readinesses' => ['supersu', 'admin'],
            'force_delete exam_readinesses' => ['supersu', 'admin']
    	];
    	foreach ($data as $k => $v) {
    		$permission = \Spatie\Permission\Models\Permission::firstOrCreate(['name' => $k, 'guard_name' => 'admin']);
    		$permission->syncRoles($v, 'admin');
    	}
    }
}
