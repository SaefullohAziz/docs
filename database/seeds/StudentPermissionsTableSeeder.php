<?php

use Illuminate\Database\Seeder;

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
    		$permission = \Spatie\Permission\Models\Permission::firstOrCreate(['name' => $k, 'guard_name' => 'admin']);
    		$permission->syncRoles($v, 'admin');
    	}
    }
}
