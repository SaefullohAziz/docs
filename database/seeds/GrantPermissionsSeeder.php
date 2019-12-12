<?php

use Illuminate\Database\Seeder;

class GrantPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
    		'access grants' => ['supersu', 'admin', 'finance', 'user'],
    		'create grants' => ['supersu', 'admin'],
    		'read grants' => ['supersu', 'admin', 'finance', 'user'],
    		'update grants'=> ['supersu', 'admin'],
            'delete grants' => ['supersu', 'admin'],
            'approval grants'=> ['supersu', 'admin'],
            'bin grants' => ['supersu', 'admin'],
            'restore grants' => ['supersu', 'admin'],
            'force_delete grants' => ['supersu', 'admin']
    	];
        collect($data)->each(function ($item, $key) {
            $permission = \Spatie\Permission\Models\Permission::firstOrCreate(['name' => $key, 'guard_name' => 'admin']);
            $permission->syncRoles($item);
        });
    }
}
