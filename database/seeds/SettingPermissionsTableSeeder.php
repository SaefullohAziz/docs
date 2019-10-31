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
    	foreach ($data as $k => $v) {
    		$permission = \Spatie\Permission\Models\Permission::firstOrCreate(['name' => $k, 'guard_name' => 'admin']);
    		$permission->syncRoles($v, 'admin');
		}
    }
}
