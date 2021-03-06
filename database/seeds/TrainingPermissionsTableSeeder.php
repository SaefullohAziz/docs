<?php

use Illuminate\Database\Seeder;

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
            'bin trainings' => ['supersu', 'admin'],
            'restore trainings' => ['supersu', 'admin'],
            'force_delete trainings' => ['supersu', 'admin'],
    	];
        collect($data)->each(function ($item, $key) {
            $permission = \Spatie\Permission\Models\Permission::firstOrCreate(['name' => $key, 'guard_name' => 'admin']);
            $permission->syncRoles($item);
        });
    }
}
