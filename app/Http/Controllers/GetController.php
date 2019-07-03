<?php

namespace App\Http\Controllers;

use App\Regency;
use App\School;
use App\Pic;
use App\Student;
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

    // PIC
    public function picBySchool(Request $request)
    {
        if ($request->ajax()) {
            if (auth()->guard('web')->check()) {
                $request->request->add(['school' => auth()->guard('web')->user()->school_id]);
            }
            $pic = Pic::bySchool($request->school)->select('pics.*')->first()->toArray();
            return response()->json(['status' => true, 'result' => $pic]);
        }
    }

    // Student
    public function generationBySchool(Request $request)
    {
        if ($request->ajax()) {
            $generations = Student::generationBySchool($request->school)->pluck('generation', 'generation')->toArray();
            return response()->json(['status' => true, 'result' => $generations]);
        }
    }

    public function schoolYearBySchool(Request $request)
    {
        if ($request->ajax()) {
            $schoolYears = Student::schoolYearBySchool($request->school)->pluck('school_year', 'school_year')->toArray();
            return response()->json(['status' => true, 'result' => $schoolYears]);
        }
    }

    public function departmentBySchool(Request $request)
    {
        if ($request->ajax()) {
            $departments = Student::departmentBySchool($request->school)->pluck('department', 'department')->toArray();
            return response()->json(['status' => true, 'result' => $departments]);
        }
    }

    public function studentBySchool(Request $request)
    {
        if ($request->ajax()) {
            $students = Student::bySchool($request->school)->pluck('name', 'id')->toArray();
            return response()->json(['status' => true, 'result' => $students]);
        }
    }

    public function studentByGeneration(Request $request)
    {
        if ($request->ajax()) {
            $students = Student::when( ! empty($request->school), function ($query) use ($request) {
                $query->bySchool($request->school);
            })->byGeneration($request->generation)->pluck('name', 'id')->toArray();
            return response()->json(['status' => true, 'result' => $students]);
        }
    }

    public function studentByGrade(Request $request)
    {
        if ($request->ajax()) {
            $students = Student::when( ! empty($request->school), function ($query) use ($request) {
                $query->bySchool($request->school);
            })->byGrade($request->grade)->pluck('name', 'id')->toArray();
            return response()->json(['status' => true, 'result' => $students]);
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
