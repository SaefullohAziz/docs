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
            'bin subsidies' => ['supersu', 'admin'],
            'restore subsidies' => ['supersu', 'admin'],
            'force_delete subsidies' => ['supersu', 'admin']
    	];
        collect($data)->each(function ($item, $key) {
            $permission = \Spatie\Permission\Models\Permission::firstOrCreate(['name' => $key, 'guard_name' => 'admin']);
            $permission->syncRoles($item);
        });
    }
}
