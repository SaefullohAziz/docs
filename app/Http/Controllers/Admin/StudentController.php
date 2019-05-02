<?php

namespace App\Http\Controllers\Admin;

use Auth;
use App\Student;
use App\Province;
use App\School;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DataTables;
use Validator;
use Spatie\Image\Image;
use Spatie\Image\Manipulations;

class StudentController extends Controller
{
    private $createdMessage;
    private $updatedMessage;
    private $deletedMessage;
    private $noPermission;
    private $table;

    /**
     * Create a new class instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
        $this->createdMessage = __('Data successfully created.');
        $this->updatedMessage = __('Data successfully updated.');
        $this->deletedMessage = __('Data successfully deleted.');
        $this->noPermission = __('You have no related permission.');
        $this->table = 'students';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if ( ! Auth::guard('admin')->user()->can('access ' . $this->table)) {
            return redirect()->route('admin.home')->with('alert-danger', $this->noPermission);
        }
        $view = [
            'title' => __('Student'),
            'breadcrumbs' => [
                route('admin.student.index') => __('Student'),
                null => 'Data'
            ],
        ];
        return view('admin.student.index', $view);
    }

    /**
     * Show a listing of the resource for datatable.
     * 
     * @param  \Illuminate\Http\Request  $request
     */
    public function list(Request $request)
    {
        if ($request->ajax()) {
            $students = Student::list($request);
            return DataTables::of($students)
                ->addColumn('DT_RowIndex', function ($data)
                {
                    return '<div class="checkbox icheck"><label><input type="checkbox" name="selectedData[]" value="'.$data->id.'"></label></div>';
                })
                ->editColumn('created_at', function($data) {
                    return (date('d-m-Y h:m:s', strtotime($data->created_at)));
                })
                ->addColumn('action', function($data) {
                    return '<a class="btn btn-sm btn-success" href="'.route('admin.student.show', $data->id).'" title="{{ __("See detail") }}"><i class="fa fa-eye"></i> {{ __("See") }}</a> <a class="btn btn-sm btn-warning" href="'.route('admin.student.edit', $data->id).'" title="{{ __("Edit") }}"><i class="fa fa-edit"></i> {{ __("Edit") }}</a>';
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
        if ( ! Auth::guard('admin')->user()->can('create ' . $this->table)) {
            return redirect()->route('admin.student.index')->with('alert-danger', $this->noPermission);
        }
        for ($i=3; $i >= -1; $i--) { 
            $schoolYears[date('Y', strtotime('-'.($i+1).' years')).'/'.date('Y', strtotime('-'.$i.' years'))] = date('Y', strtotime('-'.($i+1).' years')).'/'.date('Y', strtotime('-'.$i.' years'));
        }
        $view = [
            'title' => __('Create Student'),
            'breadcrumbs' => [
                route('admin.student.index') => __('Student'),
                null => __('Create')
            ],
            'schools' => School::pluck('name', 'id')->toArray(),
            'provinces' => Province::pluck('name', 'name')->toArray(),
            'schoolYears' => $schoolYears
        ];
        return view('admin.student.create', $view);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function show(Student $student)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function edit(Student $student)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Student $student)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if ( ! Auth::guard('admin')->user()->can('delete ' . $this->school)) {
            return response()->json(['status' => false, 'message' => $this->noPermission], 422);
        }
        Student::destroy($request->selectedData);
        return response()->json(['status' => true, 'message' => 'Data successfully deleted.']);
    }
}
