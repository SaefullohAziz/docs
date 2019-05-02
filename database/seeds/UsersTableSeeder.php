<?php

use Illuminate\Database\Seeder;
use App\Admin\User;
use Spatie\Permission\Models\Role;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $supersu = User::create([
        	'username' => 'supersu',
        	'name' => 'Supersu',
        	'email' => 'supersu@example.com',
        	'password' => bcrypt('rememberthat')
        ]);
        $supersu->assignRole('supersu');

        $admin = User::create([
        	'username' => 'admin',
        	'name' => 'Admin',
        	'email' => 'admin@example.com',
        	'password' => bcrypt('rememberthat')
        ]);
        $admin->assignRole('admin');

        $finance = User::create([
        	'username' => 'finance',
        	'name' => 'Finance',
        	'email' => 'finance@example.com',
        	'password' => bcrypt('rememberthat')
        ]);
        $finance->assignRole('finance');

        $user = User::create([
        	'username' => 'user',
        	'name' => 'User',
        	'email' => 'user@example.com',
        	'password' => bcrypt('rememberthat')
        ]);
        $user->assignRole('user');
    }
}
