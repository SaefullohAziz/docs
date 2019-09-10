<?php

namespace App\Http\Controllers\Admin;

use App\Pic;
use App\School;
use App\ExamType;
use App\ExamReadiness;
use App\ExamReadinessSchool;
use App\ExamReadinessStudent;
use App\StudentClass;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
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
        $this->table = 'subsidies';
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
            'school_references' => School::has('ExamReadinessSchool')->pluck('name', 'name')->toArray()
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
        if ( ! auth()->guard('admin')->user()->can('create ' . $this->table)) {
            return redirect()->route('admin.exam.readiness.index')->with('alert-danger', $this->noPermission);
        }
        $request->request->add(['token' => str::random(10)]);
        $examReadiness = ExamReadiness::create($request->only(['school_id', 'exam_type', 'token']));
        $this->saveStudentPartition($examReadiness, $request);
        $this->savePic($examReadiness, $request);
        return redirect(url()->previous())->with('alert-success', $this->createdMessage);
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

    /**
     * Save pic
     * 
     * @param  \App\examReadiness  $examReadiness
     * @param  \Illuminate\Http\Request  $request
     */
    public function saveStudentPartition($examReadiness, Request $request)
    {
        if ($request->student_id) {
            foreach ($request->student_id as $student_id) {
                $examReadiness->student()->attach($student_id, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    /**
     * Save pic
     * 
     * @param  \App\examReadiness  $examReadiness
     * @param  \Illuminate\Http\Request  $request
     */
    public function savePic($examReadiness, Request $request)
    {
        $pic = Pic::bySchool($request->school_id)->first();
        if ($request->isMethod('put')) {
            $schoolPic = Pic::bySchool($examReadiness->school_id)->where('id', $examReadiness->examReadinessPic->pic->id)->first();
            if ( ! $schoolPic) {
                Pic::destroy($examReadiness->examReadinessPic->pic->id);
            }
            $examReadiness->pic()->detach();
            $request->request->add(['pic' => 1]);
        }
        if ($request->pic == 1) {
            $pic = Pic::firstOrCreate([
                'name' => $request->pic_name,
                'position' => $request->pic_position,
                'phone_number' => $request->pic_phone_number,
                'email' => $request->pic_email
            ]);
        }
        $examReadiness->pic()->attach($pic->id, [
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
