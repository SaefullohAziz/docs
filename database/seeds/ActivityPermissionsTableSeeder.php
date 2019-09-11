<?php

use Illuminate\Database\Seeder;

class ActivityPermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
    		'access activities' => ['supersu', 'admin', 'finance', 'user'],
    		'create activities' => ['supersu', 'admin'],
    		'read activities' => ['supersu', 'admin'],
    		'update activities'=> ['supersu', 'admin'],
    		'delete activities' => ['supersu', 'admin'],
    		'approval activities' => ['supersu', 'admin']
    	];
    	foreach ($data as $k => $v) {
    		$permission = \Spatie\Permission\Models\Permission::create(['name' => $k, 'guard_name' => 'admin']);
    		foreach ($v as $name) {
                $permission->assignRole($name, 'admin');
    		}
    	}
    }
}
