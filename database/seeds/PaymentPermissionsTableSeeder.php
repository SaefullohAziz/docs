<?php

use Illuminate\Database\Seeder;

class PaymentPermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
    		'access payments' => ['supersu', 'admin', 'finance', 'user'],
    		'create payments' => ['supersu', 'finance'],
    		'read payments' => ['supersu', 'admin', 'finance', 'user'],
    		'update payments'=> ['supersu', 'finance'],
            'delete payments' => ['supersu', 'finance'],
            'approval payments'=> ['supersu', 'finance'],
            'bin payments' => ['supersu', 'finance'],
            'restore payments' => ['supersu', 'finance'],
            'force_delete payments' => ['supersu', 'finance'],
        ];
        collect($data)->each(function ($item, $key) {
            $permission = \Spatie\Permission\Models\Permission::firstOrCreate(['name' => $key, 'guard_name' => 'admin']);
            $permission->syncRoles($item);
        });
    }
}
