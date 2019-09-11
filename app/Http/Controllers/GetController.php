<?php

namespace App\Http\Controllers;

use App\Regency;
use App\School;
use App\Teacher;
use App\Pic;
use App\StudentClass;
use App\Student;
use App\Department;
use App\ExamType;
use Illuminate\Http\Request;

class GetController extends Controller
{
    // School
    public function regencyByProvince(Request $request)
    {
        if ($request->ajax()) {
            $regencies = Regency::getByProvinceName($request->province)->pluck('name')->toArray();
            return response()->json(['status' => true, 'result' => $regencies]);
        }
    }

    public function schoolByLevel(Request $request)
    {
        if ($request->ajax()) {
            $schools = School::byLevel($request->level)->pluck('name', 'id')->toArray();
            return response()->json(['status' => true, 'result' => $schools]);
        }
    }

    // Teacher
    public function teacherBySchool(Request $request)
    {
        if ($request->ajax()) {
            if (auth()->guard('web')->check()) {
                $request->request->add(['school' => auth()->user()->school->id]);
            }
            $teachers = Teacher::bySchool($request->school)->get()->toArray();
            return response()->json(['status' => true, 'result' => $teachers]);
        }
    }

    public function teacherBy(Request $request)
    {
        if ($request->ajax()) {
            $teacher = Teacher::find($request->teacher)->toArray();
            return response()->json(['status' => true, 'result' => $teacher]);
        }
    }

    // PIC
    public function picBySchool(Request $request)
    {
        if ($request->ajax()) {
            if (auth()->guard('web')->check()) {
                $request->request->add(['school' => auth()->user()->school->id]);
            }
            $pic = Pic::bySchool($request->school)->select('pics.*')->first()->toArray();
            return response()->json(['status' => true, 'result' => $pic]);
        }
    }

    // Class
    public function generationFromClass(Request $request)
    {
        if ($request->ajax()) {
            if (auth()->guard('web')->check()) {
                $request->request->add(['school' => auth()->user()->school->id]);
            }
            $school = School::find($request->school);
            $department = Department::find($request->department);
            $data = studentGeneration($school, $department);
            return response()->json(['status' => true, 'result' => $data]);
        }
    }

    // Student
    public function generationBySchool(Request $request)
    {
        if ($request->ajax()) {
            if (auth()->guard('web')->check()) {
                $request->request->add(['school' => auth()->user()->school->id]);
            }
            $generations = StudentClass::pluck('generation', 'generation')->toArray();
            return response()->json(['status' => true, 'result' => $generations]);
        }
    }

    public function schoolYearBySchool(Request $request)
    {
        if ($request->ajax()) {
            if (auth()->guard('web')->check()) {
                $request->request->add(['school' => auth()->user()->school->id]);
            }
            $schoolYears = StudentClass::pluck('school_year', 'school_year')->toArray();
            return response()->json(['status' => true, 'result' => $schoolYears]);
        }
    }

    public function departmentBySchool(Request $request)
    {
        if ($request->ajax()) {
            if (auth()->guard('web')->check()) {
                $request->request->add(['school' => auth()->user()->school->id]);
            }
            $departments = Department::whereHas('schoolImplementation', function ($query) use ($request) {
                $query->where('school_id', $request->school);
            })->pluck('name', 'id')->toArray();
            return response()->json(['status' => true, 'result' => $departments]);
        }
    }

    public function students(Request $request)
    {
        if ($request->ajax()) {
            if (auth()->guard('web')->check()) {
                $request->request->add(['school' => auth()->user()->school->id]);
            }
            $data = Student::whereHas('class')
            ->pluck('name', 'id')->toArray();
            return response()->json(['status' => true, 'result' => $data]);
        }
    }

    public function subExamBy(Request $request){
        if ($request->ajax()) {
            $data = ExamType::where('name', $request->type)->where('sub_name', '!=', '')->pluck('sub_name', 'id')->toArray();
            if (count($data) > 0) {
                return response()->json(['status' => true, 'result' => $data]);
            }
            return response()->json(['status' => false]);
        }
    }

    public function studentBy(Request $request)
    {
        if ($request->ajax()) {
            $student = Student::find($request->student)->toArray();
            return response()->json(['status' => true, 'result' => $student]);
        }
    }
}
