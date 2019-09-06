<?php

namespace App\Http\Controllers;

use Auth;
use DataTables;
use App\School;
use App\Status;
use App\Activity;
use App\Pic;
use App\Http\Requests\StoreActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ActivityController extends Controller
{
    private $table;
    private $types;
    private $statuses;

    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
        $this->table = 'subsidies';
        $this->types = [
                'MOU' => 'M.O.U',
                'Kunjungan_industri' => 'Kunjungan Industri',
                'SSP Pendampingan' => 'SSP Pendampingan',
                'AYR' => 'AYR',
                'Axioo_Mengajar' => 'Axioo Mengajar'
            ];
        $this->statuses = Status::pluck('name', 'id')->toArray();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $view = [
            'title' => __('Activity Submission'),
            'breadcrumbs' => [
                null => __('Activity')
            ],
            'types' => $this->types,
            'statuses' => $this->statuses,
            'schools' => School::where('id', Auth::user()->school_id)->pluck('name', 'id')->toArray(),
        ];
        return view('activity_submission.index', $view);
    }

    /**
     * Show a listing of the resource for datatable.
     * 
     * @param  \Illuminate\Http\Request  $request
     */
    public function list(Request $request)
    {
        if ($request->ajax()) {
            $activity = Activity::list($request);
            return DataTables::of($activity)
                ->addColumn('DT_RowIndex', function ($data)
                {
                    return '<div class="checkbox icheck"><label><input type="checkbox" name="selectedData[]" value="'.$data->id.'"></label></div>';
                })
                ->editColumn('created_at', function($data) {
                    return (date('d-m-Y h:m:s', strtotime($data->created_at)));
                })
                ->editColumn('submission_letter', function($data) {
                    return '<a href="'.route('download', ['dir' => encrypt('activity/submission-letter'), 'file' => encrypt($data->submission_letter)]).'" class="btn btn-sm btn-success '.( ! isset($data->submission_letter)?'disabled':'').'" title="'.__('Download').'" target="_blank"><i class="fa fa-file"></i>  '.__('Download').'</a>';
                })
                ->addColumn('action', function($data) {
                    return '<a class="btn btn-sm btn-success" href="'.route('activity.show', $data->id).'" title="'.__("See detail").'"><i class="fa fa-eye"></i>';
                })
                ->rawColumns(['DT_RowIndex', 'submission_letter', 'action'])
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
            'title' => __('Activity_Submission'),
            'breadcrumbs' => [
                route('activity.index') => __('Activity'),
                null => 'Data'
            ],
            'types' => $this->types,
            'schools' => School::where('id', Auth::user()->school_id)->pluck('name', 'id')->toArray(),
        ];
        return view('activity_submission.create', $view);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreActivity $request)
    {
        $request->request->add(['school_id' => auth()->user()->school->id]);
        $request->merge([
            'date' => date('Y-m-d', strtotime($request->date)),
        ]);
        if ($request->until_date) {
            $request->merge([
                'until_date' => date('Y-m-d', strtotime($request->until_date)),
            ]);
        }
        $activity = activity::create($request->all());
        $activity->submission_letter = $this->uploadSubmissionLetter($activity, $request);
        $activity->participant = $this->uploadParticipant($activity, $request);
        $activity->save();
        $this->savePic($activity, $request);
        return redirect(url()->previous())->with('alert-success', $this->createdMessage);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Activity  $activity
     * @return \Illuminate\Http\Response
     */
    public function show(Activity $activity)
    {
        // if (auth()->user()->cant('view', $activity)) {
        //     return redirect()->route('activity.index')->with('alert-danger', $this->noPermission);
        // }
        $view = [
            'title' => __('Activity Detail'),
            'breadcrumbs' => [
                route('activity.index') => __('Activity'),
                null => __('Detail')
            ],
            'types' => $this->types,
            'activity' => $activity,
            'statuses' => $this->statuses,
        ];
        return view('activity_submission.show', $view);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Activity  $activity
     * @return \Illuminate\Http\Response
     */
    public function edit(Activity $activity)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Activity  $activity
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Activity $activity)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Activity  $activity
     * @return \Illuminate\Http\Response
     */
    public function destroy(Activity $activity)
    {
        //
    }


    /**
     * Upload participant
     * 
     * @param  \App\Subsidy  $subsidy
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $oldFile
     * @return string
     */
    public function uploadParticipant($activity, Request $request, $oldFile = null)
    {
        if ($request->hasFile('participant')) {
            $filename = 'participant_'.date('d_m_y_h_m_s_').md5(uniqid(rand(), true)).'.'.$request->participant->extension();
            $path = $request->participant->storeAs('public/activity/participant/'.$activity->id, $filename);
            return $activity->id.'/'.$filename;
        }
        return $oldFile;
    }


    /**
     * Upload submission letter
     * 
     * @param  \App\Subsidy  $subsidy
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $oldFile
     * @return string
     */
    public function uploadSubmissionLetter($activity, Request $request, $oldFile = null)
    {
        if ($request->hasFile('submission_letter')) {
            $filename = 'submission_letter_'.date('d_m_y_h_m_s_').md5(uniqid(rand(), true)).'.'.$request->submission_letter->extension();
            $path = $request->submission_letter->storeAs('public/activity/submission-letter/'.$activity->id, $filename);
            return $activity->id.'/'.$filename;
        }
        return $oldFile;
    }

    /**
     * Save pic
     * 
     * @param  \App\Subsidy  $subsidy
     * @param  \Illuminate\Http\Request  $request
     */
    public function savePic($activity, Request $request)
    {
        $pic = Pic::bySchool($request->school_id)->first();
        if ($request->isMethod('put')) {
            $schoolPic = Pic::bySchool($activity->school_id)->where('id', $activity->activityPic->pic->id)->first();
            if ( ! $schoolPic) {
                Pic::destroy($activity->activityPic->pic->id);
            }
            $activity->pic()->detach();
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
        $activity->pic()->attach($pic->id, [
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Save status
     * 
     * @param  \App\Subsidy  $subsidy
     * @param  string  $status
     * @param  string  $desc
     */
    public function saveStatus($activity, $status, $desc)
    {
        $log = actlog($desc);
        $status = Status::byName($status)->first();
        $activity->status()->attach($status->id, [
            'log_id' => $log,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
