<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class TrainingPermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
    		'access trainings' => ['supersu', 'admin', 'finance', 'user'],
    		'create trainings' => ['supersu', 'admin'],
    		'read trainings' => ['supersu', 'admin', 'finance', 'user'],
    		'update trainings'=> ['supersu', 'admin'],
            'delete trainings' => ['supersu', 'admin'],
            'approval trainings'=> ['supersu', 'admin'],
    	];
    	foreach ($data as $k => $v) {
    		$permission = Permission::create(['name' => $k, 'guard_name' => 'admin']);
    		foreach ($v as $name) {
                $permission->assignRole($name, 'admin');
    		}
    	}
    }
}
