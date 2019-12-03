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
        collect($data)->each(function ($item, $key) {
            $permission = \Spatie\Permission\Models\Permission::firstOrCreate(['name' => $key, 'guard_name' => 'admin']);
            $permission->syncRoles($item);
        });
    }
}
