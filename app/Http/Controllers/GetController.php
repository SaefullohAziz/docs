<?php

namespace App\Http\Controllers;

use App\Admin\User as Staff;
use App\Province;
use App\Regency;
use App\SchoolLevel;
use App\SchoolStatus;
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
    // Staff
    public function staff(Request $request)
    {
        if ($request->ajax()) {
            $data = Staff::whereHas('roles', function ($query) use ($request) {
                $query->when( ! empty($request->role), function ($subQuery) use ($request) {
                    $subQuery->where('id', $request->role);
                })->when( ! empty($request->not_role), function ($subQuery) use ($request) {
                    $subQuery->where('id', '!=', $request->not_role);
                });
            })->get()->toArray();
            return response()->json(['status' => true, 'result' => $data]);
        }
    }

    // Home
    public function schoolChart(Request $request)
    {
        if ($request->ajax()) {
            $data = [
                'schoolPerProvince' => Province::when( ! empty($request->islands), function ($query) use ($request) {
                    $query->whereHas('island', function ($subQuery) use ($request) {
                        $subQuery->whereIn('id', $request->islands);
                    });
                })->when( ! empty($request->provinces), function ($query) use ($request) {
                    $query->whereIn('id', $request->provinces);
                })->withCount(['schools' => function ($query) use ($request) {
                    $query->when( ! empty($request->levels), function ($subQuery) use ($request) {
                        $subQuery->whereHas('statusUpdate.status.level', function ($subSubQuery) use ($request) {
                            $subSubQuery->whereIn('id', $request->levels);
                        });
                    });
                    $query->when( ! empty($request->statuses), function ($subQuery) use ($request) {
                        $subQuery->whereHas('statusUpdate.status', function ($subSubQuery) use ($request) {
                            $subSubQuery->whereIn('id', $request->statuses);
                        });
                    });
                }])->get()->toArray(),
                'schoolPerLevel' => SchoolLevel::when( ! empty($request->levels), function ($query) use ($request) {
                    $query->whereIn('id', $request->levels);
                })->when( ! empty($request->statuses), function ($query) use ($request) {
                    $query->whereHas('statuses', function ($subQuery) use ($request) {
                        $subQuery->whereIn('id', $request->statuses);
                    });
                })->withCount(['schools' => function ($query) use ($request) {
                    $query->when( ! empty($request->islands), function ($subQuery) use ($request) {
                        $subQuery->whereHas('province.island', function ($subSubQuery) use ($request) {
                            $subSubQuery->whereIn('id', $request->islands);
                        });
                    })->when( ! empty($request->provinces), function ($subQuery) use ($request) {
                        $subQuery->whereHas('province', function ($subSubQuery) use ($request) {
                            $subSubQuery->whereIn('id', $request->provinces);
                        });
                    });
                }])->get()->toArray(),
            ];
            return response()->json(['status' => true, 'result' => $data]);
        }
    }

    public function studentChart(Request $request)
    {
        if ($request->ajax()) {
            $data = [
                'studentPerDepartment' => Department::when( ! empty($request->departments), function ($query) use ($request) {
                    $query->whereIn('id', $request->departments);
                })->withCount(['students' => function ($query) {
                    $query->whereHas('school', function ($subQuery) {
                        $subQuery->when(auth()->guard('web')->check(), function ($subSubQuery) {
                            $subSubQuery->where('schools.id', auth()->user()->school->id);
                        });
                    });
                }])->get()->toArray(),
                'studentPerLevel' => SchoolLevel::whereIn('name', ['A', 'B', 'C'])->withCount(['students' => function ($query) use ($request) {
                    $query->whereHas('school', function ($subQuery) {
                        $subQuery->when(auth()->guard('web')->check(), function ($subSubQuery) {
                            $subSubQuery->where('schools.id', auth()->user()->school->id);
                        });
                    })->when( ! empty($request->departments), function ($subQuery) use ($request) {
                        $subQuery->whereHas('department', function ($subSubQuery) use ($request) {
                            $subSubQuery->whereIn('departments.id', $request->departments);
                        });
                    });
                }])->get()->toArray()
            ];
            return response()->json(['status' => true, 'result' => $data]);
        }
        return response()->json(['status' => false]);
    }

    // School
    public function regency(Request $request)
    {
        if ($request->ajax()) {
            $data = Regency::when( ! empty($request->provinces), function ($query) use ($request) {
                $query->whereIn('name', $request->provinces);
            })->when( ! empty($request->province), function ($query) use ($request) {
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

    public function schoolStatus(Request $request)
    {
        if ($request->ajax()) {
            $data = SchoolStatus::whereHas('level', function ($query) use ($request) {
                $query->when( ! empty($request->levels), function ($subQuery) use ($request) {
                    $subQuery->whereIn('id', $request->levels);
                })->when( ! empty($request->level), function ($subQuery) use ($request) {
                    $subQuery->where('id', $request->level);
                });
            })->pluck('name', 'id')->toArray();
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
            })->get();
            if ( ! empty($request->teacher)) {
                $data = Teacher::find($request->teacher);
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
            $data = Department::whereHas('schoolImplementations', function ($query) use ($request) {
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
            })->pluck('name', 'id');
            if ( ! empty($request->student)) {
                $data = Student::find($request->student);
            }
            $data = $data->toArray();
            return response()->json(['status' => true, 'result' => $data]);
        }
    }

    public function subExam(Request $request){
        if ($request->ajax()) {
            $data = ExamType::when( ! empty($request->type), function ($query) use ($request) {
                $query->where('name', $request->type);
            })
            ->where('sub_name', '!=', '')
            ->whereNotNull('sub_name')
            ->pluck('sub_name', 'id')
            ->toArray();
            if (count($data) == 0) {
                return response()->json(['status' => false]);
            }
            return response()->json(['status' => true, 'result' => $data]);
        }
    }
}
