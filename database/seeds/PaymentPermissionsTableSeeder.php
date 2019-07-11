<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PaymentPermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
    		'access payments' => ['supersu', 'admin', 'finance', 'user'],
    		'create payments' => ['supersu', 'finance'],
    		'read payments' => ['supersu', 'admin', 'finance', 'user'],
    		'update payments'=> ['supersu', 'finance'],
            'delete payments' => ['supersu', 'finance'],
            'approval payments'=> ['supersu', 'finance'],
    	];
    	foreach ($data as $k => $v) {
    		$permission = Permission::create(['name' => $k, 'guard_name' => 'admin']);
    		foreach ($v as $name) {
                $permission->assignRole($name, 'admin');
    		}
    	}
    }
}
