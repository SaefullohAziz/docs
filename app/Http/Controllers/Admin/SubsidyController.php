<?php

namespace App\Http\Controllers\Admin;

use Auth;
use App\Subsidy;
use App\Pic;
use App\School;
use App\Status;
use App\StudentClass;
use Illuminate\Http\Request;
use App\Events\SubsidyCanceled;
use App\Events\SubsidyRejected;
use App\Events\SubsidyApproved;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSubsidy;
use DataTables;
use Validator;
use App\Exports\SubsidiesExport;

class SubsidyController extends Controller
{
    private $table;
    private $types;
    private $grades;

    /**
     * Create a new class instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->table = 'subsidies';
        $this->types = [
            'ACP Getting started Pack (AGP) / Fast Track Program (FTP)' => 'ACP Getting started Pack (AGP) / Fast Track Program (FTP)',
            'Student Starter Pack (SSP)' => 'Student Starter Pack (SSP)',
            'Notebook Assembling & Troubleshooting Training (NATT)' => 'Notebook Assembling & Troubleshooting Training (NATT)',
            'Axioo Smart Factory' => 'Axioo Smart Factory',
            'Axioo Next Year Support' => 'Axioo Next Year Support'
        ];
        $this->grades = [
            'Kelas 10' => 'Kelas 10',
            'Kelas 11' => 'Kelas 11',
            'Kelas 12' => 'Kelas 12',
        ];
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
            'title' => __('Subsidy'),
            'breadcrumbs' => [
                route('admin.subsidy.index') => __('Subsidy'),
                null => __('Data')
            ],
            'schools' => School::pluck('name', 'id')->toArray(),
            'types' => $this->types,
            'statuses' => Status::byNames(['Created', 'Processed', 'Canceled', 'Approved', 'Payment', 'Paid', 'Sent'])->pluck('name', 'id')->toArray(),
        ];
        return view('admin.subsidy.index', $view);
    }

    /**
     * Display a listing of the deleted resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function bin()
    {
        if ( ! auth()->guard('admin')->user()->can('bin ' . $this->table)) {
            return redirect()->route('admin.subsidy.index')->with('alert-danger', __($this->noPermission));
        }
        $view = [
            'back' => route('admin.subsidy.index'),
            'title' => __('Deleted Subsidy'),
            'breadcrumbs' => [
                route('admin.subsidy.index') => __('Subsidy'),
                null => __('Deleted')
            ],
        ];
        return view('admin.subsidy.bin', $view);
    }

    /**
     * Show a listing of the resource for datatable.
     * 
     * @param  \Illuminate\Http\Request  $request
     */
    public function list(Request $request)
    {
        if ($request->ajax()) {
            $subsidies = Subsidy::list($request);
            return DataTables::of($subsidies)
                ->addColumn('DT_RowIndex', function ($data) {
                    return '<div class="checkbox icheck"><label><input type="checkbox" name="selectedData[]" value="'.$data->id.'"></label></div>';
                })
                ->editColumn('created_at', function($data) {
                    return (date('d-m-Y H:i:s', strtotime($data->created_at)));
                })
                ->editColumn('submission_letter', function($data) {
                    $file = $data->submission_letter;
                    if (strpos($file, '/') == false) {
                        $file = date('Y-m-d', strtotime($data->created_at)) . '/' . $file;
                    }
                    return '<a href="'.route('download', ['dir' => encrypt('subsidy/submission-letter'), 'file' => encrypt($file)]).'" class="btn btn-sm btn-success '.( ! isset($data->submission_letter)?'disabled':'').'" title="'.__('Download').'" target="_blank"><i class="fa fa-file"></i>  '.__('Download').'</a>';
                })
                ->editColumn('report', function($data) {
                    $file = $data->report;
                    if (strpos($file, '/') == false) {
                        $file = date('Y-m-d', strtotime($data->created_at)) . '/' . $file;
                    }
                    return '<a href="'.route('download', ['dir' => encrypt('subsidy/report'), 'file' => encrypt($file)]).'" class="btn btn-sm btn-success '.( ! isset($data->report)?'disabled':'').'" title="'.__('Download').'" target="_blank"><i class="fa fa-file"></i>  '.__('Download').'</a>';
                })
                ->editColumn('school', function($data) {
                    return '<a href="' . route('admin.school.show', $data->school_id) . '" class="btn">'. $data->school .'</a>';
                })
                ->editColumn('status', function($data) {
                    return $data->status.' by '.$data->status_by. ' at '. $data->status_at;
                })
                ->addColumn('action', function($data) {
                    return '<a class="btn btn-sm btn-success" href="'.route('admin.subsidy.show', $data->id).'" title="'.__("See detail").'"><i class="fa fa-eye"></i> '.__("See").'</a> <a class="btn btn-sm btn-warning" href="'.route('admin.subsidy.edit', $data->id).'" title="'.__("Edit").'"><i class="fa fa-edit"></i> '.__("Edit").'</a>';
                })
                ->rawColumns(['DT_RowIndex', 'submission_letter', 'report', 'school', 'action'])
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
            return redirect()->route('admin.subsidy.index')->with('alert-danger', __($this->noPermission));
        }
        if (auth()->guard('admin')->user()->cant('adminCreate', Subsidy::class)) {
            return redirect()->route('admin.subsidy.index')->with('alert-danger', __($this->unauthorizedMessage));
        }
        $view = [
            'back' => route('admin.subsidy.index'),
            'title' => __('Create Subsidy'),
            'breadcrumbs' => [
                route('admin.subsidy.index') => __('Subsidy'),
                null => __('Create')
            ],
            'schools' => School::pluck('name', 'id')->toArray(),
            'types' => $this->types,
            'studentYears' => ['Tahun ke-2' => 'Tahun ke-2', 'Tahun ke-3' => 'Tahun ke-3'],
            'generations' => StudentClass::orderBy('generation', 'asc')->pluck('generation', 'generation')->unique()->toArray(),
            'grades' => $this->grades
        ];
        return view('admin.subsidy.create', $view);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSubsidy $request)
    {
        if ( ! auth()->guard('admin')->user()->can('create ' . $this->table)) {
            return redirect()->route('admin.subsidy.index')->with('alert-danger', __($this->noPermission));
        }
        if (auth()->guard('admin')->user()->cant('adminCreate', Subsidy::class)) {
            return redirect()->route('admin.subsidy.index')->with('alert-danger', __($this->unauthorizedMessage));
        }
        $subsidy = Subsidy::create($request->all());
        $subsidy->submission_letter = $this->uploadSubmissionLetter($subsidy, $request);
        $subsidy->report = $this->uploadReport($subsidy, $request);
        $subsidy->save();
        $this->saveSspStudent($subsidy, $request);
        $this->savePic($subsidy, $request);
        return redirect(url()->previous())->with('alert-success', __($this->createdMessage) . ' ' . __('Please wait for our approval for this submission.'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Subsidy  $subsidy
     * @return \Illuminate\Http\Response
     */
    public function show(Subsidy $subsidy)
    {
        if ( ! auth()->guard('admin')->user()->can('read ' . $this->table)) {
            return redirect()->route('admin.subsidy.index')->with('alert-danger', __($this->noPermission));
        }
        $view = [
            'back' => route('admin.subsidy.index'),
            'title' => __('Subsidy Detail'),
            'breadcrumbs' => [
                route('admin.subsidy.index') => __('Subsidy'),
                null => __('Detail')
            ],
            'schools' => School::pluck('name', 'id')->toArray(),
            'types' => $this->types,
            'studentYears' => ['Tahun ke-2' => 'Tahun ke-2', 'Tahun ke-3' => 'Tahun ke-3'],
            'generations' => StudentClass::orderBy('generation', 'asc')->pluck('generation', 'generation')->unique()->toArray(),
            'grades' => $this->grades,
            'data' => $subsidy->load(['students' => function ($student) {
                $student->orderBy('class_id', 'asc')->orderBy('name', 'asc');
            }]),
        ];
        return view('admin.subsidy.show', $view);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Subsidy  $subsidy
     * @return \Illuminate\Http\Response
     */
    public function edit(Subsidy $subsidy)
    {
        if ( ! auth()->guard('admin')->user()->can('update ' . $this->table)) {
            return redirect()->route('admin.subsidy.index')->with('alert-danger', __($this->noPermission));
        }
        $view = [
            'back' => route('admin.subsidy.index'),
            'title' => __('Edit Subsidy'),
            'breadcrumbs' => [
                route('admin.subsidy.index') => __('Subsidy'),
                null => __('Edit')
            ],
            'schools' => School::pluck('name', 'id')->toArray(),
            'types' => $this->types,
            'studentYears' => ['Tahun ke-2' => 'Tahun ke-2', 'Tahun ke-3' => 'Tahun ke-3'],
            'generations' => StudentClass::orderBy('generation', 'asc')->pluck('generation', 'generation')->unique()->toArray(),
            'grades' => $this->grades,
            'data' => $subsidy
        ];
        return view('admin.subsidy.edit', $view);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Subsidy  $subsidy
     * @return \Illuminate\Http\Response
     */
    public function update(StoreSubsidy $request, Subsidy $subsidy)
    {
        if ( ! auth()->guard('admin')->user()->can('update ' . $this->table)) {
            return redirect()->route('admin.subsidy.index')->with('alert-danger', __($this->noPermission));
        }
        $subsidy->fill($request->all());
        $subsidy->submission_letter = $this->uploadSubmissionLetter($subsidy, $request, $subsidy->submission_letter);
        $subsidy->report = $this->uploadReport($subsidy, $request, $subsidy->report);
        $subsidy->save();
        $this->saveSspStudent($subsidy, $request);
        $this->savePic($subsidy, $request);
        return redirect(url()->previous())->with('alert-success', __($this->updatedMessage));
    }

    /**
     * Upload submission letter
     * 
     * @param  \App\Subsidy  $subsidy
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $oldFile
     * @return string
     */
    public function uploadSubmissionLetter($subsidy, Request $request, $oldFile = null)
    {
        if ($request->hasFile('submission_letter')) {
            $filename = 'submission_letter_'.date('d_m_Y_H_i_s_').md5(uniqid(rand(), true)).'.'.$request->submission_letter->extension();
            $path = $request->submission_letter->storeAs('public/subsidy/submission-letter/'.$subsidy->id, $filename);
            return $subsidy->id.'/'.$filename;
        }
        return $oldFile;
    }

     /**
     * Upload report
     * 
     * @param  \App\Subsidy  $subsidy
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $oldFile
     * @return string
     */
    public function uploadReport($subsidy, Request $request, $oldFile = null)
    {
        if ($request->hasFile('report')) {
            $filename = 'report_'.date('d_m_Y_H_i_s_').md5(uniqid(rand(), true)).'.'.$request->report->extension();
            $path = $request->report->storeAs('public/subsidy/report/'.$subsidy->id, $filename);
            return $subsidy->id.'/'.$filename;
        }
        return $oldFile;
    }

    /**
     * Save ssp student
     * 
     * @param  \App\Subsidy  $subsidy
     * @param  \Illuminate\Http\Request  $request
     */
    public function saveSspStudent($subsidy, Request $request)
    {
        if ($request->isMethod('put')) {
            $subsidy->students()->detach();
        }
        if ($request->type == 'Student Starter Pack (SSP)') {
            if ($request->filled('student_id')) {
                for ($i=0; $i < count($request->student_id); $i++) { 
                    $subsidy->students()->attach($request->student_id[$i], [
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }

    /**
     * Save pic
     * 
     * @param  \App\Subsidy  $subsidy
     * @param  \Illuminate\Http\Request  $request
     */
    public function savePic($subsidy, Request $request)
    {
        $pic = Pic::bySchool($request->school_id)->first();
        if ($request->isMethod('put')) {
            $schoolPic = Pic::bySchool($subsidy->school_id)->where('id', $subsidy->subsidyPic->pic->id)->first();
            if ( ! $schoolPic) {
                Pic::destroy($subsidy->subsidyPic->pic->id);
            }
            $subsidy->pic()->detach();
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
        $subsidy->pic()->attach($pic->id, [
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Cancel subsidy
     * 
     * @param  \Illuminate\Http\Request  $request
     */
    public function cancel(Request $request)
    {
        if ($request->ajax()) {
            if ( ! auth()->guard('admin')->user()->can('approval ' . $this->table)) {
                return response()->json(['status' => false, 'message' => __($this->noPermission)], 422);
            }
            event(new SubsidyCanceled($request));
            return response()->json(['status' => true, 'message' => __($this->updatedMessage)]);
        }
    }

    /**
     * Reject subsidy
     * 
     * @param  \Illuminate\Http\Request  $request
     */
    public function reject(Request $request)
    {
        if ($request->ajax()) {
            if ( ! auth()->guard('admin')->user()->can('approval ' . $this->table)) {
                return response()->json(['status' => false, 'message' => __($this->noPermission)], 422);
            }
            event(new SubsidyRejected($request));
            return response()->json(['status' => true, 'message' => __($this->updatedMessage)]);
        }
    }

    /**
     * Approve subsidy
     * 
     * @param  \Illuminate\Http\Request  $request
     */
    public function approve(Request $request)
    {
        if ($request->ajax()) {
            if ( ! auth()->guard('admin')->user()->can('approval ' . $this->table)) {
                return response()->json(['status' => false, 'message' => __($this->noPermission)], 422);
            }
            event(new SubsidyApproved($request));
            return response()->json(['status' => true, 'message' => __($this->updatedMessage)]);
        }
    }

    /**
     * Export subsidy listing as Excel
     * 
     * @param  \Illuminate\Http\Request  $request
     */
    public function export(Request $request)
    {
        return (new SubsidiesExport($request))->download('subsidy-'.date('d-m-Y-h-i-s').'.xlsx');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Subsidy  $subsidy
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if ($request->ajax()) {
            if ( ! auth()->guard('admin')->user()->can('delete ' . $this->table)) {
                return response()->json(['status' => false, 'message' => __($this->noPermission)], 422);
            }
            Subsidy::destroy($request->selectedData);
            return response()->json(['status' => true, 'message' => __($this->deletedMessage)]);
        }
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
        Subsidy::onlyTrashed()->whereIn('id', $request->selectedData)->restore();
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
        Subsidy::onlyTrashed()->whereIn('id', $request->selectedData)->forceDelete();
        return response()->json(['status' => true, 'message' => __($this->deletedMessage)]);
    }
}
