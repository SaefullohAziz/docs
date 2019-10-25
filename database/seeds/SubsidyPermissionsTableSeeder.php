<?php

use Illuminate\Database\Seeder;

class SubsidyPermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
    		'access subsidies' => ['supersu', 'admin', 'finance', 'user'],
    		'create subsidies' => ['supersu', 'admin'],
    		'read subsidies' => ['supersu', 'admin', 'finance', 'user'],
    		'update subsidies'=> ['supersu', 'admin'],
            'delete subsidies' => ['supersu', 'admin'],
            'approval subsidies'=> ['supersu', 'admin'],
    	];
    	foreach ($data as $k => $v) {
    		$permission = \Spatie\Permission\Models\Permission::firstOrCreate(['name' => $k, 'guard_name' => 'admin']);
    		$permission->syncRoles($v, 'admin');
    	}
    }
}
