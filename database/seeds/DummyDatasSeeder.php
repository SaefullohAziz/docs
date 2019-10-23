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
                                                $school->statusUpdates()->createMany(factory(App\SchoolStatusUpdate::class, 10)->make()->toArray());
                                                $school->teachers()->createMany(factory(App\Teacher::class, 6)->make()->toArray());
                                                $school->implementations()->save(factory(App\SchoolImplementation::class)->make());
                                                foreach ($school->implementations as $implementation) {
                                                    $school->studentClasses()->save(factory(App\StudentClass::class)->make([
                                                        'department_id' => $implementation->department->id
                                                    ]));
                                                }
                                                foreach ($school->studentClasses as $class) {
                                                    $class->students()->createMany(factory(App\Student::class, 33)->make()->toArray());
                                                }
                                                $school->user()->save(factory(App\User::class)->make());
                                            });
        $visitationDestinations = factory(App\VisitationDestination::class, 3)->create();
    }
}
