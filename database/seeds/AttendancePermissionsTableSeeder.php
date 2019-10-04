<?php

use Illuminate\Database\Seeder;

class AttendancePermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
    		'access attendances' => ['supersu', 'admin', 'finance', 'user'],
    		'create attendances' => ['supersu', 'admin'],
    		'read attendances' => ['supersu', 'admin'],
    		'update attendances'=> ['supersu', 'admin'],
    		'delete attendances' => ['supersu', 'admin'],
    		'approval attendances' => ['supersu', 'admin'],
            'bin attendances' => ['supersu', 'admin'],
            'restore attendances' => ['supersu', 'admin'],
            'force_delete attendances' => ['supersu', 'admin']
    	];
    	foreach ($data as $k => $v) {
    		$permission = \Spatie\Permission\Models\Permission::firstOrCreate(['name' => $k, 'guard_name' => 'admin']);
    		$permission->syncRoles($v, 'admin');
    	}
    }
}
