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
        collect($data)->each(function ($item, $key) {
            $permission = \Spatie\Permission\Models\Permission::firstOrCreate(['name' => $key, 'guard_name' => 'admin']);
            $permission->syncRoles($item);
        });
    }
}
