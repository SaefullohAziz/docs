<?php

use App\SchoolStatus;
use App\School;
use App\Pic;
use App\Admin\User as Staff;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DummyDatasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /**
         * Dummy School 1
         */
        // School
        $school = School::firstOrCreate(
            ['name' => 'STM Greget'],
            [
                'type' => 'Swasta',
                'address' => 'Jln. Panjang Pokona',
                'province' => 'Jawa Barat', 
                'regency' => 'Kabupaten Ciamis', 
                'police_number' => 'Z', 
                'since' => '1998', 
                'school_phone_number' => '854615465424',
                'school_email' => 'mail@site.com', 
                'school_web' => 'http://site.com', 
                'total_student' => '1000', 
                'department' => 'Teknik Komputer dan Jaringan', 
                'iso_certificate' => 'Sudah', 
                'mikrotik_academy' => 'Belum', 
                'headmaster_name' => 'Me', 
                'headmaster_phone_number' => '86725715715', 
                'headmaster_email' => 'mail1@site.com', 
                'reference' => 'Sekolah Peserta / Sekolah Binaan', 
                'dealer_name' => null, 
                'dealer_phone_number' => null, 
                'dealer_email' => null, 
                'proposal' => 'Belum', 
                'code' => Str::random(10),
            ]
        );
        // School PIC
        $pic = Pic::firstOrcreate([
            'name' => 'Me',
            'position' => 'Guru',
            'phone_number' => '85725362522',
            'email' => 'me@pic.com'
        ]);
        $school->pic()->sync([$pic->id => [
            'created_at' => now(),
            'updated_at' => now()
        ]]);
        // School Status
        $status = SchoolStatus::byName('Daftar')->first();
        $user = Staff::where('username', 'admin')->first();
        $school->status()->sync([$status->id => [
            'created_by' => 'staff',
            'staff_id' => $user->id,
            'created_at' => now(),
            'updated_at' => now()
        ]]);
        // User
        $school->user()->firstOrCreate(
            ['username' => 'GRG07619-01'],
            [
                'name' => 'Otong', 
                'email' => 'otong@mail.com', 
                'password' => bcrypt('!Indo!Joss!'),
            ]
        );


        /**
         * Dummy School 2
         */
        // School
        $school = School::firstOrCreate(
            ['name' => 'STM Maju Mulu'],
            [
                'type' => 'Swasta',
                'address' => 'Jln. Panjang Pokona',
                'province' => 'Jawa Barat', 
                'regency' => 'Kabupaten Ciamis', 
                'police_number' => 'Z', 
                'since' => '1998', 
                'school_phone_number' => '854615465424',
                'school_email' => 'mail@stm3.com', 
                'school_web' => 'http://stm3.com', 
                'total_student' => '1000', 
                'department' => 'Teknik Komputer dan Jaringan', 
                'iso_certificate' => 'Sudah', 
                'mikrotik_academy' => 'Belum', 
                'headmaster_name' => 'Me', 
                'headmaster_phone_number' => '86725715715', 
                'headmaster_email' => 'mail1@stm3.com', 
                'reference' => 'Sekolah Peserta / Sekolah Binaan', 
                'dealer_name' => null, 
                'dealer_phone_number' => null, 
                'dealer_email' => null, 
                'proposal' => 'Belum', 
                'code' => Str::random(10),
            ]
        );
        // School PIC
        $pic = Pic::firstOrcreate([
            'name' => 'Me',
            'position' => 'Guru',
            'phone_number' => '85725362522',
            'email' => 'pic@stm3.com'
        ]);
        $school->pic()->sync([$pic->id => [
            'created_at' => now(),
            'updated_at' => now()
        ]]);
        // School Status
        $status = SchoolStatus::byName('Daftar')->first();
        $user = Staff::where('username', 'admin')->first();
        $school->status()->sync([$status->id => [
            'created_by' => 'staff',
            'staff_id' => $user->id,
            'created_at' => now(),
            'updated_at' => now()
        ]]);
        // User
        $school->user()->firstOrCreate(
            ['username' => 'STM307619-01'],
            [
                'name' => 'Joni', 
                'email' => 'joni@stm3.com', 
                'password' => bcrypt('!Indo!Joss!'),
            ]
        );
    }
}
