<?php

namespace App\Http\Controllers\Admin;

use App\Pic;
use App\School;
use App\ExamType;
use App\Student;
use App\ExamReadiness;
use App\ExamReadinessSchool;
use App\ExamReadinessStudent;
use App\StudentClass;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreExamReadiness;
use Illuminate\Support\Str;
use DataTables;
use App\Exports\ExamReadinessesExport;

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
     * Display a listing of the deleted resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function bin()
    {
        if ( ! auth()->guard('admin')->user()->can('bin ' . $this->table)) {
            return redirect()->route('admin.home')->with('alert-danger', __($this->noPermission));
        }
        $view = [
            'title' => __('Deleted Exam Readiness'),
            'breadcrumbs' => [
                route('admin.exam.readiness.index') => __('Exam Readiness'),
                null => __('Bin')
            ],
            'subtitle' => __('Bin'),
            'schools' => School::orderBy('name', 'asc')->pluck('name', 'id')->toArray(),
            'types' => ExamType::orderBy('name', 'asc')->pluck('name', 'name')->toArray(),
        ];

        return view('admin.exam.readiness.bin', $view);
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
            'referenceSchools' => School::has('examReadinessSchool')->pluck('name', 'name')->toArray()
        ];
        return view('admin.exam.readiness.create', $view);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreExamReadiness $request)
    {
        if ( ! auth()->guard('admin')->user()->can('create ' . $this->table)) {
            return redirect()->route('admin.exam.readiness.index')->with('alert-danger', __($this->noPermission));
        }
        if ($request->sub_exam_type && is_array($request->sub_exam_type)) {
            $request->merge(['sub_exam_type' => implode(', ', $request->sub_exam_type)]);
        }
        $request->request->add(['token' => Str::random(10)]);
        $examReadiness = ExamReadiness::create($request->all());
        $this->saveStudentPartiticipant($examReadiness, $request);
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
        if ( ! auth()->guard('admin')->user()->can('read ' . $this->table)) {
            return redirect()->route('admin.exam.readiness.index')->with('alert-danger', __($this->noPermission));
        }
        $view = [
            'title' => __('Detail Exam Readiness'),
            'breadcrumbs' => [
                route('admin.exam.readiness.index') => __('Exam Readiness'),
                null => __('Detail')
            ],
            'schools' => School::orderBy('name', 'asc')->pluck('name', 'id')->toArray(),
            'types' => ExamType::orderBy('name', 'asc')->pluck('name', 'name')->toArray(),
            'subTypes' => ExamType::where('name', $examReadiness->exam_type)->whereNotNull('sub_name')->pluck('sub_name', 'sub_name')->toArray(),
            'generations' => StudentClass::orderBy('generation', 'asc')->pluck('generation', 'generation')->toArray(),
            'referenceSchools' => School::has('examReadinessSchool')->pluck('name', 'name')->toArray(),
            'data' => $examReadiness,
        ];
        return view('admin.exam.readiness.show', $view);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ExamReadiness  $examReadiness
     * @return \Illuminate\Http\Response
     */
    public function edit(ExamReadiness $examReadiness)
    {
        if ( ! auth()->guard('admin')->user()->can('update ' . $this->table)) {
            return redirect()->route('admin.exam.readiness.index')->with('alert-danger', __($this->noPermission));
        }
        $view = [
            'title' => __('Edit Exam Readiness'),
            'breadcrumbs' => [
                route('admin.exam.readiness.index') => __('Exam Readiness'),
                null => __('Edit')
            ],
            'schools' => School::orderBy('name', 'asc')->pluck('name', 'id')->toArray(),
            'types' => ExamType::orderBy('name', 'asc')->pluck('name', 'name')->toArray(),
            'subTypes' => ExamType::where('name', $examReadiness->exam_type)->whereNotNull('sub_name')->pluck('sub_name', 'sub_name')->toArray(),
            'generations' => StudentClass::orderBy('generation', 'asc')->pluck('generation', 'generation')->toArray(),
            'referenceSchools' => School::has('examReadinessSchool')->pluck('name', 'name')->toArray(),
            'data' => $examReadiness,
        ];
        return view('admin.exam.readiness.edit', $view);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ExamReadiness  $examReadiness
     * @return \Illuminate\Http\Response
     */
    public function update(StoreExamReadiness $request, ExamReadiness $examReadiness)
    {
        if ( ! auth()->guard('admin')->user()->can('update ' . $this->table)) {
            return redirect()->route('admin.exam.readiness.index')->with('alert-danger', __($this->noPermission));
        }
        if ($request->sub_exam_type && is_array($request->sub_exam_type)) {
            $request->merge(['sub_exam_type' => implode(', ', $request->sub_exam_type)]);
        }
        $examReadiness->fill($request->all());
        $examReadiness->save();
        $this->saveStudentPartiticipant($examReadiness, $request);
        $this->savePic($examReadiness, $request);
        return redirect(url()->previous())->with('alert-success', __($this->updatedMessage));
    }

    /**
     * Save pic
     * 
     * @param  \App\examReadiness  $examReadiness
     * @param  \Illuminate\Http\Request  $request
     */
    public function saveStudentPartiticipant($examReadiness, Request $request)
    {
        if ($request->student_id) {
            $examReadiness->students()->sync($request->student_id);
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
        $examReadiness->pic()->sync([$pic->id]);
    }

    /**
     * Export listing as Excel
     * 
     * @param  \Illuminate\Http\Request  $request
     */
    public function export(Request $request)
    {
        return (new ExamReadinessesExport($request))->download('exam-readiness-'.date('d-m-Y-h-m-s').'.xlsx');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if ( ! auth()->guard('admin')->user()->can('delete ' . $this->table)) {
            return response()->json(['status' => false, 'message' => __($this->noPermission)], 422);
        }
        ExamReadiness::destroy($request->selectedData);
        return response()->json(['status' => true, 'message' => __($this->deletedMessage)]);
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function restore(Request $request)
    {
        if ( ! auth()->guard('admin')->user()->can('restore ' . $this->table)) {
            return response()->json(['status' => false, 'message' => __($this->noPermission)], 422);
        }
        ExamReadiness::whereIn('id', $request->selectedData)->restore();
        return response()->json(['status' => true, 'message' => __($this->restoredMessage)]);
    }

    /**
     * Remove permanently the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroyPermanently(Request $request)
    {
        if ( ! auth()->guard('admin')->user()->can('force_delete ' . $this->table)) {
            return response()->json(['status' => false, 'message' => __($this->noPermission)], 422);
        }
        ExamReadiness::whereIn('id', $request->selectedData)->forceDelete();
        return response()->json(['status' => true, 'message' => __($this->deletedMessage)]);
    }
}
