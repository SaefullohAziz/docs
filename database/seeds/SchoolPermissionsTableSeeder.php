<?php

use Illuminate\Database\Seeder;

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
    		'delete schools' => ['supersu'],
            'bin schools' => ['supersu', 'admin'],
            'restore schools' => ['supersu', 'admin'],
            'force_delete schools' => ['supersu', 'admin']
    	];
    	foreach ($data as $k => $v) {
			$permission = \Spatie\Permission\Models\Permission::firstOrCreate(['name' => $k, 'guard_name' => 'admin']);
			$permission->syncRoles($v, 'admin');
    	}
    }
}
