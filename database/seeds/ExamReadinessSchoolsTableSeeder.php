<?php

use Illuminate\Database\Seeder;

class ExamReadinessSchoolsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $examType = \App\ExamType::where('name', 'MTCNA')->first();
        $schools = \App\School::limit(3)->get();
        foreach ($schools as $school) {
            $school->examReadinessSchool()->create([
                'exam_type_id' => $examType->id
            ]);
        }
    }
}
