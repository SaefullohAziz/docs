<?php

namespace App\Http\Controllers\Admin;

use App\School;
use App\SchoolLevel;
use App\Department;
use App\StudentClass;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStudentClass;
use DataTables;
use App\Exports\StudentsExport;

class StudentClassController extends Controller
{
    private $table;

    /**
     * Create a new class instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth:admin');
        $this->table = 'student_classes';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if ( ! auth()->guard('admin')->user()->can('access ' . $this->table)) {
            return redirect()->route('admin.home')->with('alert-danger', __($this->noPermission));
        }
        $view = [
            'title' => __('Class'),
            'breadcrumbs' => [
                route('admin.class.index') => __('Class'),
                null => 'Data'
            ],
            'levels' => SchoolLevel::pluck('name', 'id')->toArray(),
            'schools' => School::orderBy('name', 'asc')->pluck('name', 'id')->toArray(),
            'generations' => StudentClass::pluck('generation', 'generation')->toArray(),
            'schoolYears' => StudentClass::pluck('school_year', 'school_year')->toArray(),
            'departments' => Department::pluck('name', 'id')->toArray(),
        ];
        return view('admin.class.index', $view);
    }

    /**
     * Show a listing of the resource for datatable.
     * 
     * @param  \Illuminate\Http\Request  $request
     */
    public function list(Request $request)
    {
        if ($request->ajax()) {
            $classes = StudentClass::list($request);
            return DataTables::of($classes)
                ->addColumn('DT_RowIndex', function ($data) {
                    return '<div class="checkbox icheck"><label><input type="checkbox" name="selectedData[]" value="'.$data->id.'"></label></div>';
                })
                ->editColumn('created_at', function($data) {
                    return (date('d-m-Y h:m:s', strtotime($data->created_at)));
                })
                ->addColumn('action', function($data) {
                    return '<a class="btn btn-sm btn-secondary" href="'.route('admin.class.student.index', $data->id).'" title="'.__("See students").'"><i class="fa fa-users"></i> '.__("Student").'</a> <a class="btn btn-sm btn-success" href="'.route('admin.class.show', $data->id).'" title="'.__("See detail").'"><i class="fa fa-eye"></i> '.__("See").'</a> <a class="btn btn-sm btn-warning" href="'.route('admin.class.edit', $data->id).'" title="'.__("Edit").'"><i class="fa fa-edit"></i> '.__("Edit").'</a>';
                })
                ->rawColumns(['DT_RowIndex', 'action'])
                ->make(true);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if ( ! auth()->guard('admin')->user()->can('create ' . $this->table)) {
            return redirect()->route('admin.class.index')->with('alert-danger', __($this->noPermission));
        }
        $view = [
            'title' => __('Create Class'),
            'breadcrumbs' => [
                route('admin.class.index') => __('Class'),
                null => __('Create')
            ],
            'schools' => School::has('implementation')->orderBy('name', 'asc')->pluck('name', 'id')->toArray(),
            'schoolYear' => schoolYear()
        ];
        return view('admin.class.create', $view);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreStudentClass  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreStudentClass $request)
    {
        if ( ! auth()->guard('admin')->user()->can('create ' . $this->table)) {
            return redirect()->route('admin.class.index')->with('alert-danger', __($this->noPermission));
        }
        $school = School::find($request->school_id);
        if ($school->implementations->count() > 1) {
            $department = Department::find($request->department_id);
        } elseif ($school->implementations->count() <= 1) {
            $department = Department::find($school->implementations[0]->department->id);
            $request->request->add([
                'department_id' => $department->id
            ]);
        }
        $request->request->add([
            'generation' => studentGeneration($school, $department),
            'school_year' => schoolYear(),
            'grade' => 'Kelas 10'
        ]);
        StudentClass::firstOrCreate($request->except(['_token', 'submit']));
        return redirect(url()->previous())->with('alert-success', __($this->createdMessage));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\StudentClass  $studentClass
     * @return \Illuminate\Http\Response
     */
    public function show(StudentClass $studentClass)
    {
        if ( ! auth()->guard('admin')->user()->can('read ' . $this->table)) {
            return redirect()->route('admin.class.index')->with('alert-danger', __($this->noPermission));
        }
        $view = [
            'title' => __('Class Detail'),
            'breadcrumbs' => [
                route('admin.class.index') => __('Class'),
                null => __('Detail')
            ],
            'schools' => School::has('implementations')->orderBy('name', 'asc')->pluck('name', 'id')->toArray(),
            'departments' => Department::whereHas('schoolImplementations', function ($query) use ($studentClass) {
                $query->where('school_id', $studentClass->school_id);
            })->pluck('name', 'id')->toArray(),
            'data' => $studentClass,
        ];
        return view('admin.class.show', $view);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\StudentClass  $studentClass
     * @return \Illuminate\Http\Response
     */
    public function edit(StudentClass $studentClass)
    {
        if ( ! auth()->guard('admin')->user()->can('update ' . $this->table)) {
            return redirect()->route('admin.class.index')->with('alert-danger', __($this->noPermission));
        }
        if (auth()->guard('admin')->user()->cant('adminUpdate', $studentClass)) {
            return redirect()->route('admin.class.index')->with('alert-danger', __($this->unauthorizedMessage) . ' ' . __('This class already has students.'));
        }
        $view = [
            'title' => __('Edit Class'),
            'breadcrumbs' => [
                route('admin.class.index') => __('Class'),
                null => __('Edit')
            ],
            'schools' => School::has('implementations')->orderBy('name', 'asc')->pluck('name', 'id')->toArray(),
            'departments' => Department::whereHas('schoolImplementations', function ($query) use ($studentClass) {
                $query->where('school_id', $studentClass->school_id);
            })->pluck('name', 'id')->toArray(),
            'data' => $studentClass,
        ];
        return view('admin.class.edit', $view);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\StudentClass  $studentClass
     * @return \Illuminate\Http\Response
     */
    public function update(StoreStudentClass $request, StudentClass $studentClass)
    {
        if ( ! auth()->guard('admin')->user()->can('update ' . $this->table)) {
            return redirect()->route('admin.class.index')->with('alert-danger', __($this->noPermission));
        }
        if (auth()->guard('admin')->user()->cant('adminUpdate', $studentClass)) {
            return redirect()->route('admin.class.index')->with('alert-danger', __($this->unauthorizedMessage) . ' ' . __('This class already has students.'));
        }
        $school = School::find($request->school_id);
        if ($school->implementations->count() > 1) {
            $department = Department::find($request->department_id);
        } elseif ($school->implementations->count() <= 1) {
            $department = Department::find($school->implementations[0]->department->id);
            $request->request->add([
                'department_id' => $department->id
            ]);
        }
        $request->request->add([
            'generation' => studentGeneration($school, $department),
            'school_year' => schoolYear(),
            'grade' => 'Kelas 10'
        ]);
        $studentClass->fill($request->except(['_token', 'submit']));
        $studentClass->save();
        return redirect(url()->previous())->with('alert-success', __($this->createdMessage));
    }

    /**
     * Export class listing as Excel
     * 
     * @param  \Illuminate\Http\Request  $request
     */
    public function export(Request $request)
    {
        return (new StudentsExport($request))->download('student-'.date('d-m-Y-h-m-s').'.xlsx');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if ( ! auth()->guard('admin')->user()->can('delete ' . $this->school)) {
            return response()->json(['status' => false, 'message' => __($this->noPermission)], 422);
        }
        StudentClass::destroy($request->selectedData);
        return response()->json(['status' => true, 'message' => __($this->deletedMessage)]);
    }

    /**
     * Open the class for deny some management.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function open(Request $request)
    {
        if ( ! auth()->guard('admin')->user()->can('open ' . $this->school)) {
            return response()->json(['status' => false, 'message' => __($this->noPermission)], 422);
        }
        StudentClass::whereIn('id', $request->selectedData)->update(['closed_at' => null]);

        return response()->json(['status' => true, 'message' => __($this->updatedMessage)]);
    }

    /**
     * Close the class for deny some management.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function close(Request $request)
    {
        if ( ! auth()->guard('admin')->user()->can('close ' . $this->school)) {
            return response()->json(['status' => false, 'message' => __($this->noPermission)], 422);
        }
        StudentClass::whereIn('id', $request->selectedData)->update(['closed_at' => now()]);

        return response()->json(['status' => true, 'message' => __($this->updatedMessage)]);
    }
}
