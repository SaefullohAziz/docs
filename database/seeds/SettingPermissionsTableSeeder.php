<?php

use Illuminate\Database\Seeder;

class SettingPermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
    		'access settings' => ['supersu', 'admin'],
    		'access general settings' => ['supersu'],
    		'access role settings' => ['supersu'],
    		'access permission settings' => ['supersu'],
    		'access form settings' => ['supersu', 'admin'],
    		'access training settings' => ['supersu', 'admin'],
    		'access exam_readiness settings' => ['supersu', 'admin'],
    	];
        collect($data)->each(function ($item, $key) {
            $permission = \Spatie\Permission\Models\Permission::firstOrCreate(['name' => $key, 'guard_name' => 'admin']);
            $permission->syncRoles($item);
        });
    }
}
