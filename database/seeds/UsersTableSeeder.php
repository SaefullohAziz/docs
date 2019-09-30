<?php

use Illuminate\Database\Seeder;
use App\Admin\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

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
        	'password' => Hash::make('rememberthat')
        ]);
        $supersu->assignRole('supersu');

        $admin = User::create([
        	'username' => 'admin',
        	'name' => 'Admin',
        	'email' => 'admin@example.com',
        	'password' => Hash::make('rememberthat')
        ]);
        $admin->assignRole('admin');

        $finance = User::create([
        	'username' => 'finance',
        	'name' => 'Finance',
        	'email' => 'finance@example.com',
        	'password' => Hash::make('rememberthat')
        ]);
        $finance->assignRole('finance');

        $user = User::create([
        	'username' => 'user',
        	'name' => 'User',
        	'email' => 'user@example.com',
        	'password' => Hash::make('rememberthat')
        ]);
        $user->assignRole('user');
    }
}
