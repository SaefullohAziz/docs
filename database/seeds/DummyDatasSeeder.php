<?php

use Illuminate\Database\Seeder;

class DummyDatasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $schools = factory(App\School::class, 20)
                                            ->create()
                                            ->each(function ($school) {
                                                $school->schoolPic()->save(factory(App\SchoolPic::class)->make());
                                                $school->statusUpdate()->save(factory(App\SchoolStatusUpdate::class)->make());
                                                $school->implementation()->save(factory(App\SchoolImplementation::class)->make());
                                                foreach ($school->implementation as $implementation) {
                                                    $school->studentClass()->save(factory(App\StudentClass::class)->make([
                                                        'department_id' => $implementation->department->id
                                                    ]));
                                                }
                                                foreach ($school->studentClass as $class) {
                                                    $class->student()->createMany(factory(App\Student::class, 33)->make()->toArray());
                                                }
                                                $school->user()->save(factory(App\User::class)->make());
                                            });
    }
}
