<?php

namespace App\Http\Controllers\Admin;

use App\School;
use App\ExamType;
use App\ExamReadiness;
use App\ExamReadinessSchool;
use App\StudentClass;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DataTables;

class ExamReadinessController extends Controller
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
        $this->table = 'exam_readinesses';
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
            'title' => __('Exam Readiness'),
            'breadcrumbs' => [
                route('admin.exam.readiness.index') => __('Exam Readiness'),
                null => 'Data'
            ],
            'schools' => School::orderBy('name', 'asc')->pluck('name', 'id')->toArray(),
            'types' => ExamType::orderBy('name', 'asc')->pluck('name', 'name')->toArray(),
        ];
        return view('admin.exam.readiness.index', $view);
    }

    /**
     * Show a listing of the resource for datatable.
     * 
     * @param  \Illuminate\Http\Request  $request
     */
    public function list(Request $request)
    {
        if ($request->ajax()) {
            $examReadiness = ExamReadiness::list($request);
            return DataTables::of($examReadiness)
                ->addColumn('DT_RowIndex', function ($data) {
                    return '<div class="checkbox icheck"><label><input type="checkbox" name="selectedData[]" value="'.$data->id.'"></label></div>';
                })
                ->editColumn('created_at', function($data) {
                    return (date('d-m-Y h:m:s', strtotime($data->created_at)));
                })
                ->addColumn('student', function ($data) {
                    return '';
                })
                ->addColumn('action', function($data) {
                    return '<a class="btn btn-sm btn-success" href="'.route('admin.exam.readiness.show', $data->id).'" title="'.__("See detail").'"><i class="fa fa-eye"></i> '.__("See").'</a> <a class="btn btn-sm btn-warning" href="'.route('admin.exam.readiness.edit', $data->id).'" title="'.__("Edit").'"><i class="fa fa-edit"></i> '.__("Edit").'</a>';
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
            return redirect()->route('admin.exam.readiness.index')->with('alert-danger', __($this->noPermission));
        }
        $view = [
            'title' => __('Create Exam Readiness'),
            'breadcrumbs' => [
                route('admin.exam.readiness.index') => __('Exam Readiness'),
                null => __('Create')
            ],
            'schools' => School::orderBy('name', 'asc')->pluck('name', 'id')->toArray(),
            'types' => ExamType::orderBy('name', 'asc')->pluck('name', 'name')->toArray(),
            'generations' => StudentClass::orderBy('generation', 'asc')->pluck('generation', 'generation')->toArray(),
            'referenceSchool' => ExamReadinessSchool::school()->pluck('name', 'name')->toArray(),
        ];
        return view('admin.exam.readiness.create', $view);
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
     * @param  \App\ExamReadiness  $examReadiness
     * @return \Illuminate\Http\Response
     */
    public function show(ExamReadiness $examReadiness)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ExamReadiness  $examReadiness
     * @return \Illuminate\Http\Response
     */
    public function edit(ExamReadiness $examReadiness)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ExamReadiness  $examReadiness
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ExamReadiness $examReadiness)
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
        ExamReadiness::destroy($request->selectedData);
        return response()->json(['status' => true, 'message' => __('Data successfully deleted.')]);
    }
}
