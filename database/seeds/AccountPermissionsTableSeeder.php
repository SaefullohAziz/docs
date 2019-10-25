<?php

use Illuminate\Database\Seeder;

class AccountPermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
    		'access accounts' => ['supersu', 'admin', 'finance', 'user'],
    		'create accounts' => ['supersu', 'admin'],
    		'read accounts' => ['supersu', 'admin'],
    		'update accounts'=> ['supersu', 'admin'],
    		'delete accounts' => ['supersu', 'admin']
    	];
    	foreach ($data as $k => $v) {
    		$permission = \Spatie\Permission\Models\Permission::firstOrCreate(['name' => $k, 'guard_name' => 'admin']);
    		$permission->syncRoles($v, 'admin');
    	}
    }
}