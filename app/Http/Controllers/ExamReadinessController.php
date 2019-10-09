<?php

namespace App\Http\Controllers;

use App\Pic;
use App\School;
use App\Student;
use App\ExamType;
use App\ExamReadiness;
use App\ExamReadinessSchool;
use App\ExamReadinessStudent;
use App\StudentClass;
use Illuminate\Http\Request;
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
        $this->middleware('auth');
        $this->table = 'exam_readinesses';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $view = [
            'title' => __('Exam Readiness'),
            'breadcrumbs' => [
                route('exam.readiness.index') => __('Exam Readiness'),
                null => 'Data'
            ],
            'types' => ExamType::orderBy('name', 'asc')->pluck('name', 'name')->toArray(),
        ];
        return view('exam.readiness.index', $view);
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
                    return '<a class="btn btn-sm btn-success" href="'.route('exam.readiness.show', $data->id).'" title="'.__("See detail").'"><i class="fa fa-eye"></i> '.__("See").'</a>';
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
        $view = [
            'title' => __('Create Exam Readiness'),
            'breadcrumbs' => [
                route('exam.readiness.index') => __('Exam Readiness'),
                null => __('Create')
            ],
            'types' => ExamType::when(auth()->user()->cant('create', ExamReadiness::class), function ($query) {
                $query->where('name', 'Axioo');
                $query->orWhere('name', 'Remidial Axioo');
            })->orderBy('name', 'asc')->pluck('name', 'name')->toArray(),
            'generations' => StudentClass::where('school_id', auth()->user()->school->id)->orderBy('generation', 'asc')->pluck('generation', 'generation')->toArray(),
            'referenceSchools' => School::has('ExamReadinessSchool')->pluck('name', 'name')->toArray()
        ];
        return view('exam.readiness.create', $view);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->request->add(['school_id' => auth()->user()->school->id]);
        if ($request->filled('exam_sub_types')) {
            $exam_sub = implode($request->exam_sub_types, ', ');
            $request->request->add(['sub_exam_type' => $exam_sub]);
        }
        $request->request->add(['token' => Str::random(10)]);
        $examReadiness = ExamReadiness::create($request->all());
        $this->saveStudent($examReadiness, $request);
        $this->savePic($examReadiness, $request);
        return redirect(url()->previous())->with('alert-success', __($this->createdMessage));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ExamReadiness  $examReadiness
     * @return \Illuminate\Http\Response
     */
    public function show(ExamReadiness $examReadiness)
    {
        $view = [
            'title' => __('Detail Exam Readiness'),
            'breadcrumbs' => [
                route('exam.readiness.index') => __('Exam Readiness'),
                null => __('Detail')
            ],
            'schools' => School::orderBy('name', 'asc')->pluck('name', 'id')->toArray(),
            'types' => ExamType::orderBy('name', 'asc')->pluck('name', 'name')->toArray(),
            'generations' => StudentClass::orderBy('generation', 'asc')->pluck('generation', 'generation')->toArray(),
            'reference_schools' => School::has('ExamReadinessSchool')->pluck('name', 'name')->toArray(),
            'reference_student' => $examReadiness->student(),
            'generation' => ['Angkatan 1' => 'Angkatan 1'],
            'examReadinessStudents' => Student::has('examReadinessStudent')->get(),
            'examReadiness' => $examReadiness
        ];

        return view('exam.readiness.show', $view);
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
     * Save pic
     * 
     * @param  \App\examReadiness  $examReadiness
     * @param  \Illuminate\Http\Request  $request
     */
    public function saveStudent($examReadiness, Request $request)
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ExamReadiness  $examReadiness
     * @return \Illuminate\Http\Response
     */
    public function destroy(ExamReadiness $examReadiness)
    {
        //
    }
}
