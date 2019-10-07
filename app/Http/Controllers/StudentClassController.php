<?php

namespace App\Http\Controllers;

use App\School;
use App\Department;
use App\StudentClass;
use Illuminate\Http\Request;
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
        $this->middleware('auth');
        $this->table = 'student_classes';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $view = [
            'title' => __('Class'),
            'breadcrumbs' => [
                route('class.index') => __('Class'),
                null => 'Data'
            ],
            'generations' => StudentClass::pluck('generation', 'generation')->toArray(),
            'schoolYears' => StudentClass::pluck('school_year', 'school_year')->toArray(),
            'departments' => Department::pluck('name', 'id')->toArray(),
            'school' => School::find(auth()->user()->school->id),
        ];
        return view('class.index', $view);
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
                    return '<a class="btn btn-sm btn-secondary" href="'.route('class.student.index', $data->id).'" title="'.__("See students").'"><i class="fa fa-users"></i> '.__("Student").'</a> <a class="btn btn-sm btn-success" href="'.route('class.show', $data->id).'" title="'.__("See detail").'"><i class="fa fa-eye"></i> '.__("See").'</a> <a class="btn btn-sm btn-warning" href="'.route('class.edit', $data->id).'" title="'.__("Edit").'"><i class="fa fa-edit"></i> '.__("Edit").'</a>';
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
        $school = School::find(auth()->user()->school->id);
        $view = [
            'title' => __('Create Class'),
            'breadcrumbs' => [
                route('class.index') => __('Class'),
                null => __('Create')
            ],
            'school' => $school,
            'departments' => Department::whereHas('schoolImplementation', function ($query) use ($school) {
                $query->where('school_id', $school->id);
            })->pluck('name', 'id')->toArray(),
            'generation' => null,
            'schoolYear' => schoolYear()
        ];
        $department = Department::find($school->implementation[0]->department->id);
        if ($school->implementation->count() == 1) {
            $addonView = [
                'generation' => studentGeneration($school, $department),
            ];
            $view = array_merge($view, $addonView);
        }
        return view('class.create', $view);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $school = School::find(auth()->user()->school_id);
        if ($school->implementation->count() > 1) {
            $department = Department::find($request->department_id);
        } elseif ($school->implementation->count() <= 1) {
            $department = Department::find($school->implementation[0]->department->id);
            $request->request->add([
                'department_id' => $department->id
            ]);
        }
        $request->request->add([
            'generation' => studentGeneration($school, $department),
            'school_year' => schoolYear(),
            'grade' => 'Kelas 10'
        ]);
        $school->studentClass()->firstOrCreate($request->except(['_token', 'submit']));
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
        $view = [
            'title' => __('Class Detail'),
            'breadcrumbs' => [
                route('class.index') => __('Class'),
                null => __('Detail')
            ],
            'departments' => Department::whereHas('schoolImplementation', function ($query) use ($studentClass) {
                $query->where('school_id', $studentClass->school_id);
            })->pluck('name', 'id')->toArray(),
            'data' => $studentClass,
        ];
        return view('class.show', $view);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\StudentClass  $studentClass
     * @return \Illuminate\Http\Response
     */
    public function edit(StudentClass $studentClass)
    {
        if (auth()->user()->cant('update', $studentClass)) {
            return redirect()->route('class.index')->with('alert-danger', __($this->unauthorizedMessage) . ' ' . __('This class already has students.'));
        }
        $view = [
            'title' => __('Edit Class'),
            'breadcrumbs' => [
                route('class.index') => __('Class'),
                null => __('Edit')
            ],
            'departments' => Department::whereHas('schoolImplementation', function ($query) use ($studentClass) {
                $query->where('school_id', $studentClass->school_id);
            })->pluck('name', 'id')->toArray(),
            'data' => $studentClass,
        ];
        return view('class.edit', $view);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\StudentClass  $studentClass
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StudentClass $studentClass)
    {
        if (auth()->user()->cant('update', $studentClass)) {
            return redirect()->route('class.index')->with('alert-danger', __($this->unauthorizedMessage) . ' ' . __('This class already has students.'));
        }
        $school = School::find(auth()->user()->school->id);
        if ($school->implementation->count() > 1) {
            $department = Department::find($request->department_id);
        } elseif ($school->implementation->count() <= 1) {
            $department = Department::find($school->implementation[0]->department->id);
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
     * @param  \App\StudentClass  $studentClass
     * @return \Illuminate\Http\Response
     */
    public function destroy(StudentClass $studentClass)
    {
        StudentClass::destroy($request->selectedData);
        return response()->json(['status' => true, 'message' => __($this->deletedMessage)]);
    }
}
