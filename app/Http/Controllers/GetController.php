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
    public function regency(Request $request)
    {
        if ($request->ajax()) {
            $data = Regency::when( ! empty($request->school), function ($query) use ($request) {
                $query->getByProvinceName($request->province);
            })->pluck('name')->toArray();
            return response()->json(['status' => true, 'result' => $data]);
        }
    }

    public function school(Request $request)
    {
        if ($request->ajax()) {
            $data = School::when( ! empty($request->level), function ($query) use ($request) {
                $query->byLevel($request->level);
            })->orderBy('name', 'asc')->pluck('name', 'id')->toArray();
            return response()->json(['status' => true, 'result' => $data]);
        }
    }

    // Teacher
    public function teacher(Request $request)
    {
        if ($request->ajax()) {
            if (auth()->guard('web')->check()) {
                $request->request->add(['school' => auth()->user()->school->id]);
            }
            $data = Teacher::when( ! empty($request->school), function ($query) use ($request) {
                $query->bySchool($request->school);
            })
            ->when( ! empty($request->teacher), function ($query) use ($request) {
                $query->find($request->teacher);
            });
            if (empty($request->teacher)) {
                $data = $data->get();
            }
            $data = $data->toArray();
            return response()->json(['status' => true, 'result' => $data]);
        }
    }

    // PIC
    public function pic(Request $request)
    {
        if ($request->ajax()) {
            if (auth()->guard('web')->check()) {
                $request->request->add(['school' => auth()->user()->school->id]);
            }
            $data = Pic::when( ! empty($request->school), function ($query) use ($request) {
                $query->bySchool($request->school);
            })->select('pics.*')->first()->toArray();
            return response()->json(['status' => true, 'result' => $data]);
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
    public function generation(Request $request)
    {
        if ($request->ajax()) {
            if (auth()->guard('web')->check()) {
                $request->request->add(['school' => auth()->user()->school->id]);
            }
            $data = StudentClass::when( ! empty($request->school), function ($query) use ($request) {
                $query->where('school_id', $request->school);
            })->pluck('generation', 'generation')->toArray();
            return response()->json(['status' => true, 'result' => $data]);
        }
    }

    public function schoolYear(Request $request)
    {
        if ($request->ajax()) {
            if (auth()->guard('web')->check()) {
                $request->request->add(['school' => auth()->user()->school->id]);
            }
            $data = StudentClass::when( ! empty($request->school), function ($query) use ($request) {
                $query->where('school_id', $request->school);
            })->pluck('school_year', 'school_year')->toArray();
            return response()->json(['status' => true, 'result' => $data]);
        }
    }

    public function department(Request $request)
    {
        if ($request->ajax()) {
            if (auth()->guard('web')->check()) {
                $request->request->add(['school' => auth()->user()->school->id]);
            }
            $data = Department::whereHas('schoolImplementation', function ($query) use ($request) {
                $query->when( ! empty($request->school), function ($subQuery) use ($request) {
                    $subQuery->where('school_id', $request->school);
                });
            })->pluck('name', 'id')->toArray();
            return response()->json(['status' => true, 'result' => $data]);
        }
    }

    public function student(Request $request)
    {
        if ($request->ajax()) {
            if (auth()->guard('web')->check()) {
                $request->request->add(['school' => auth()->user()->school->id]);
            }
            $data = Student::whereHas('class', function ($query) use ($request) {
                $query->when( ! empty($request->school), function ($subQuery) use ($request) {
                    $subQuery->where('student_classes.school_id', $request->school);
                });
                $query->when( ! empty($request->generation), function ($subQuery) use ($request) {
                    $subQuery->where('student_classes.generation', $request->generation);
                });
                $query->when( ! empty($request->grade), function ($subQuery) use ($request) {
                    $subQuery->where('student_classes.grade', $request->grade);
                });
            })
            ->when( ! empty($request->ssp), function ($query) {
                $query->has('sspStudent');
            })
            ->when( ! empty($request->student), function ($query) use ($request) {
                $query->find($request->student);
            });
            if (empty($request->student)) {
                $data = $data->pluck('name', 'id');
            }
            $data = $data->toArray();
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
