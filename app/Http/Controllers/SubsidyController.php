<?php

namespace App\Http\Controllers;

use Auth;
use App\Subsidy;
use App\Pic;
use App\School;
use App\Status;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSubsidy;
use DataTables;
use Validator;
use App\Exports\SubsidiesExport;

class SubsidyController extends Controller
{
    private $table;
    private $types;
    private $generations;
    private $grades;

    /**
     * Create a new class instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
        $this->middleware('level:C|B|A');
        $this->table = 'subsidies';
        $this->types = [
            'ACP Getting started Pack (AGP) / Fast Track Program (FTP)' => 'ACP Getting started Pack (AGP) / Fast Track Program (FTP)',
            'Student Starter Pack (SSP)' => 'Student Starter Pack (SSP)',
            'Notebook Assembling & Troubleshooting Training (NATT)' => 'Notebook Assembling & Troubleshooting Training (NATT)',
            'Axioo Smart Factory' => 'Axioo Smart Factory',
            'Axioo Next Year Support' => 'Axioo Next Year Support'
        ];
        $this->generations = [
            'Angkatan 1' => 'Angkatan 1',
            'Angkatan 2' => 'Angkatan 2',
            'Angkatan 3' => 'Angkatan 3',
            'Angkatan 4' => 'Angkatan 4',
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
        $view = [
            'title' => __('Subsidy'),
            'breadcrumbs' => [
                route('subsidy.index') => __('Subsidy'),
                null => __('Data')
            ],
            'types' => $this->types,
            'statuses' => Status::byNames(['Created', 'Processed', 'Canceled', 'Approved', 'Payment', 'Paid', 'Sent'])->pluck('name', 'id')->toArray(),
        ];
        return view('subsidy.index', $view);
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
                ->addColumn('DT_RowIndex', function ($data)
                {
                    return '<div class="checkbox icheck"><label><input type="checkbox" name="selectedData[]" value="'.$data->id.'"></label></div>';
                })
                ->editColumn('created_at', function($data) {
                    return (date('d-m-Y h:m:s', strtotime($data->created_at)));
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
                ->editColumn('status', function($data) {
                    return $data->status;
                })
                ->addColumn('action', function($data) {
                    return '<a class="btn btn-sm btn-success" href="'.route('subsidy.show', $data->id).'" title="'.__("See detail").'"><i class="fa fa-eye"></i> '.__("See").'</a> <a class="btn btn-sm btn-warning" href="'.route('subsidy.edit', $data->id).'" title="'.__("Edit").'"><i class="fa fa-edit"></i> '.__("Edit").'</a>';
                })
                ->rawColumns(['DT_RowIndex', 'submission_letter', 'report', 'action'])
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
        if (auth()->user()->cant('create', Subsidy::class)) {
            return redirect()->route('subsidy.index')->with('alert-danger', __($this->unauthorizedMessage));
        }
        $view = [
            'title' => __('Create Subsidy'),
            'breadcrumbs' => [
                route('subsidy.index') => __('Subsidy'),
                null => __('Create')
            ],
            'types' => $this->types,
            'studentYears' => ['Tahun ke-2' => 'Tahun ke-2', 'Tahun ke-3' => 'Tahun ke-3'],
            'generations' => $this->generations,
            'grades' => $this->grades
        ];
        return view('subsidy.create', $view);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSubsidy $request)
    {
        if (auth()->user()->cant('create', Subsidy::class)) {
            return redirect()->route('subsidy.index')->with('alert-danger', __($this->unauthorizedMessage));
        }
        $request->request->add(['school_id' => auth()->user()->school->id]);
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
        if (auth()->user()->cant('view', $subsidy)) {
            return redirect()->route('subsidy.index')->with('alert-danger', __($this->unauthorizedMessage));
        }
        $view = [
            'title' => __('Subsidy Detail'),
            'breadcrumbs' => [
                route('subsidy.index') => __('Subsidy'),
                null => __('Detail')
            ],
            'types' => $this->types,
            'studentYears' => ['Tahun ke-2' => 'Tahun ke-2', 'Tahun ke-3' => 'Tahun ke-3'],
            'generations' => $this->generations,
            'grades' => $this->grades,
            'subsidy' => $subsidy
        ];
        return view('subsidy.show', $view);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Subsidy  $subsidy
     * @return \Illuminate\Http\Response
     */
    public function edit(Subsidy $subsidy)
    {
        if (auth()->user()->cant('update', $subsidy)) {
            return redirect()->route('subsidy.index')->with('alert-danger', __($this->noPermission));
        }
        $view = [
            'title' => __('Edit Subsidy'),
            'breadcrumbs' => [
                route('subsidy.index') => __('Subsidy'),
                null => __('Edit')
            ],
            'types' => $this->types,
            'studentYears' => ['Tahun ke-2' => 'Tahun ke-2', 'Tahun ke-3' => 'Tahun ke-3'],
            'generations' => $this->generations,
            'grades' => $this->grades,
            'subsidy' => $subsidy
        ];
        return view('subsidy.edit', $view);
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
        if (auth()->user()->cant('update', $subsidy)) {
            return redirect()->route('subsidy.index')->with('alert-danger', __($this->noPermission));
        }
        $request->request->add(['school_id' => auth()->user()->school->id]);
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
            $filename = 'submission_letter_'.date('d_m_y_h_m_s_').md5(uniqid(rand(), true)).'.'.$request->submission_letter->extension();
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
            $filename = 'report_'.date('d_m_y_h_m_s_').md5(uniqid(rand(), true)).'.'.$request->report->extension();
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
     * Export subsidy listing as Excel
     * 
     * @param  \Illuminate\Http\Request  $request
     */
    public function export(Request $request)
    {
        return (new SubsidiesExport($request))->download('subsidy-'.date('d-m-Y-h-m-s').'.xlsx');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Subsidy  $subsidy
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        Subsidy::destroy($request->selectedData);
        return response()->json(['status' => true, 'message' => __($this->deletedMessage)]);
    }
}
