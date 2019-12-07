<?php

namespace App\Http\Controllers;

use App\Admin\User as Staff;
use App\Province;
use App\Regency;
use App\SchoolLevel;
use App\SchoolStatus;
use App\School;
use App\Teacher;
use App\Training;
use App\Pic;
use App\StudentClass;
use App\Student;
use App\Department;
use App\ExamType;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            $data = School::with(['statusUpdate.status.level'])->when($request->filled('level'), function ($query) use ($request) {
                $query->byLevel($request->level);
            })->whereHas('status', function ($status) use ($request) {
                $status->when($request->filled('status'), function ($query) {
                    $query->where('school_statuses.id', $request->status);
                });
            })->when($request->filled('search'), function ($query) use ($request) {
                $query->where('name', 'LIKE', "%$request->search%");
            })->when($request->filled('school'), function ($query) use ($request) {
                $query->where('id', $request->school);
            })->orderBy('name', 'asc');
            if ($request->filled('school')) {
                $data = $data->first();
            } else {
                $data = $data->pluck('name', 'id');
            }
            if ($request->filled('search')) {
                $data = $data->map(function ($item, $key) {
                    return ['id' => $key, 'text' => $item];
                })->values();
            }
            $data = $data->toArray();
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
            $data = Student::when(auth()->guard('web')->check(), function ($query) use ($request) {
                $query->whereHas('class', function ($subQuery) {
                    $subQuery->where('student_classes.school_id', auth()->user()->school->id);
                })->when( ! empty($request->exam_type), function ($subQuery) use ($request) {
                    $examType = \App\ExamType::where('name', $request->exam_type);
                    if ( ! empty($request->sub_exam_type)) {
                        if ( ! is_array($request->sub_exam_type)) {
                            $request->merge(['sub_exam_type' => [$request->sub_exam_type]]);
                        }
                        $examType->whereIn('sub_name', $request->sub_exam_type);
                    }
                    $slugs = $examType->pluck('slug')->toArray();
                    $setting = collect(json_decode(setting('exam_readiness_settings')))->whereIn('slug', $slugs);
                    // Department
                    $departments = $setting->map(function ($item, $key) {
                        return $item->department_limiter_slug;
                    })->map(function ($item, $key) {
                        return json_decode(setting($item));
                    })->flatten()->unique();
                    if ($departments->count()) {
                        $subQuery->whereHas('department', function ($subSubQuery) use ($departments) {
                            $subSubQuery->whereIn('abbreviation', $departments->toArray());
                        });
                    }
                    // SSP
                    $sspStudent = $setting->map(function ($item, $key) {
                        return $item->ssp_limiter_slug;
                    })->map(function ($item, $key) {
                        return json_decode(setting($item));
                    })->sum();
                    if ($sspStudent) {
                        $subQuery->whereHas('subsidy.subsidyStatus.status', function ($subSubQuery) {
                            $subSubQuery->where('name', 'Paid');
                        });
                    }
                });
            })->when(empty($request->student), function ($query) use ($request) {
                $query->whereHas('class', function ($subQuery) use ($request) {
                    $subQuery->when( ! empty($request->school), function ($subSubQuery) use ($request) {
                        $subSubQuery->where('student_classes.school_id', $request->school);
                    })->when( ! empty($request->generation), function ($subSubQuery) use ($request) {
                        $subSubQuery->where('student_classes.generation', $request->generation);
                    })->when( ! empty($request->grade), function ($subSubQuery) use ($request) {
                        $subSubQuery->where('student_classes.grade', $request->grade);
                    });
                });
            })->when( ! empty($request->student), function ($query) use ($request) {
                $query->where('id', $request->student);
            });
            if (empty($request->student)) {
                $data = $data->pluck('name', 'id');
            } else {
                $data = $data->select('students.*')->first();
            }
            $data = $data->toArray();
            return response()->json(['status' => true, 'result' => $data]);
        }
    }

    public function subExam(Request $request){
        if ($request->ajax()) {
            $data = ExamType::when( ! empty($request->type), function ($query) use ($request) {
                $query->where('name', $request->type);
            })->when(auth()->guard('web')->check(), function ($query) {
                $sspStudent = Student::whereHas('subsidy.subsidyStatus.status', function ($query) {
                    $query->where('name', 'Paid');
                })->whereHas('class', function ($query) {
                    $query->where('school_id', auth()->user()->school->id);
                })->get();
                $departments = auth()->user()->school->implementedDepartments->pluck('abbreviation')->toArray();
                $data = json_decode(setting('exam_readiness_settings'), true);
                // Filter opened type
                $filter = array_filter($data, function ($item) {
                    return setting($item['is_opened_slug']) == 1;
                });
                // Filter by implemented department
                $filter = array_filter($filter, function ($item) {
                    $selectedDepartments = json_decode(setting($item['department_limiter_slug']), true);
                    if (auth()->user()->school->implementedDepartments->count()) {
                        $departments = auth()->user()->school->implementedDepartments->pluck('abbreviation')->toArray();
                        return count($selectedDepartments) == 0 || count(array_intersect($selectedDepartments, $departments)) > 0;
                    }
                    return count($selectedDepartments) == 0;
                });
                // Filter by SSP student
                $filter = array_filter($filter, function ($item) use ($sspStudent) {
                    if ($sspStudent->count() > 0) {
                        return setting($item['ssp_limiter_slug']) == 0 || setting($item['ssp_limiter_slug']) == 1;
                    }
                    return setting($item['ssp_limiter_slug']) == 0;
                });
                $slugs = array_column($filter, 'slug');
                $query->whereIn('slug', $slugs);
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

    public function schoolStatusUpdate(Request $request)
    {
        if ($request->ajax()) {
            $data = [
                'school' => School::where('id', $request->school)->select('id', 'name')->first()->toArray(),
                'statuses' => SchoolStatus::orderBy('order_by', 'asc')->pluck('name', 'id')->toArray(),
            ];
            return response()->json(['status' => true, 'result' => $data]);
        }
    }

    public function role(Request $request)
    {
        if ($request->ajax()) {
            $data = Role::when($request->filled('permission'), function ($query) use ($request) {
                $query->whereHas('permissions', function ($permission) use ($request) {
                    $permission->where('permissions.id', $request->permission);
                });
            })->select('id', DB::raw('CONCAT(UCASE(SUBSTRING(name, 1, 1)), LOWER(SUBSTRING(name, 2))) as name'))->get()->toArray();
            return response()->json(['status' => true, 'result' => $data]);
        }
    }

    public function permission(Request $request)
    {
        if ($request->ajax()) {
            $data = Permission::with(['roles:id,name'])->when($request->filled('permission'), function ($query) use ($request) {
                $query->where('id', $request->permission);
            })->select('id', DB::raw('CONCAT(UCASE(SUBSTRING(name, 1, 1)), LOWER(SUBSTRING(name, 2))) as name'))->first()->toArray();
            return response()->json(['status' => true, 'result' => $data]);
        }
    }
}
