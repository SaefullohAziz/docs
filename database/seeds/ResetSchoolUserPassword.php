<?php

use Illuminate\Database\Seeder;

class ResetSchoolUserPassword extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = \App\User::select('id')->get();
        $users->each(function ($item, $key) {
            $user = \App\User::find($item->id);
            $user->fill([
                'password' => \Illuminate\Support\Facades\Hash::make('Indonesia2017!'),
            ]);
            $user->save();
        });
    }
}
