<?php

use Illuminate\Database\Seeder;

class TeacherPermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
    		'access teachers' => ['supersu', 'admin', 'finance', 'user'],
    		'create teachers' => ['supersu', 'admin'],
    		'read teachers' => ['supersu', 'admin', 'finance', 'user'],
    		'update teachers'=> ['supersu', 'admin'],
            'delete teachers' => ['supersu', 'admin'],
            'approval teachers'=> ['supersu', 'admin'],
    	];
    	foreach ($data as $k => $v) {
    		$permission = Spatie\Permission\Models\Permission::create(['name' => $k, 'guard_name' => 'admin']);
    		foreach ($v as $name) {
                $permission->assignRole($name, 'admin');
    		}
    	}
    }
}
