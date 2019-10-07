<?php

namespace App\Http\Controllers\Admin;

use Auth;
use DataTables;
use App\School;
use App\Status;
use App\Activity;
use App\ActivityPic;
use App\Pic;
use Illuminate\Http\Request;
use App\Http\Requests\StoreActivity;
use Illuminate\Support\Facades\DB;
use App\Events\ActivityCanceled;
use App\Events\ActivityRejected;
use App\Events\ActivityApproved;
use App\Http\Controllers\Controller;

class ActivityController extends Controller
{
    protected $table;
    protected $types;
    protected $statuses;

    public function __construct()
    {

        parent::__construct();
        $this->middleware('auth:admin');
        $this->table = 'activities';
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
        if ( ! auth()->guard('admin')->user()->can('create ' . $this->table)) {
            return redirect()->route('admin.home')->with('alert-danger', __($this->noPermission));
        }
        $view = [
            'title' => __('Activity Submission'),
            'breadcrumbs' => [
                route('admin.activity.index') => __('Activity Submission'),
                null => 'Data'
            ],
            'schools' => School::orderBy('name', 'asc')->pluck('name', 'id')->toArray(),
            'types' => $this->types,
            'statuses' => $this->statuses,
        ];
        return view('admin.activity.index', $view);
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
                    return '<a class="btn btn-sm btn-success" href="'.route('admin.activity.show', $data->id).'" title="'.__("See detail").'"><i class="fa fa-eye"></i> '.__("See").'</a> <a class="btn btn-sm btn-warning" href="'.route('admin.activity.edit', $data->id).'" title="'.__("Edit").'"><i class="fa fa-edit"></i> '.__("Edit").'</a>';
                })->editColumn('status', function($data) {
                    return $data->status;
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
        if ( ! auth()->guard('admin')->user()->can('create ' . $this->table)) {
            return redirect()->route('admin.activity.index')->with('alert-danger', __($this->noPermission));
        }
        $view = [
            'title' => __('Create Activity Submission'),
            'breadcrumbs' => [
                route('admin.activity.index') => __('Activity Submission'),
                null => __('Create')
            ],
            'schools' => School::orderBy('name', 'asc')->pluck('name', 'id')->toArray(),
            'types' => $this->types,
        ];
        return view('admin.activity.create', $view);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreActivity $request)
    {
        if ( ! auth()->guard('admin')->user()->can('create ' . $this->table)) {
            return redirect()->route('admin.activity.index')->with('alert-danger', __($this->noPermission));
        }
        $request->merge([
            'date' => date('Y-m-d', strtotime($request->date)),
        ]);
        if ($request->until_date) {
            $request->merge([
                'until_date' => date('Y-m-d', strtotime($request->until_date)),
            ]);
        }
        $activity = Activity::create($request->all());
        $activity->submission_letter = $this->uploadSubmissionLetter($activity, $request);
        $activity->participant = $this->uploadParticipant($activity, $request);
        $activity->save();
        $this->savePic($activity, $request);
        return redirect(url()->previous())->with('alert-success', __($this->createdMessage));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Activity  $activity
     * @return \Illuminate\Http\Response
     */
    public function show(Activity $activity)
    {
        if ( ! auth()->guard('admin')->user()->can('read ' . $this->table)) {
            return redirect()->route('admin.activity.index')->with('alert-danger', __($this->noPermission));
        }
        $view = [
            'title' => __('Activity Submission Detail'),
            'breadcrumbs' => [
                route('admin.activity.index') => __('Activity Submission'),
                null => __('Edit')
            ],
            'schools' => School::orderBy('name', 'asc')->pluck('name', 'id')->toArray(),
            'types' => $this->types,
            'statuses' => $this->statuses,
            'data' => $activity,
        ];
        return view('admin.activity.show', $view);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Activity  $activity
     * @return \Illuminate\Http\Response
     */
    public function edit(Activity $activity)
    {
        if ( ! auth()->guard('admin')->user()->can('update ' . $this->table)) {
            return redirect()->route('admin.activity.index')->with('alert-danger', __($this->noPermission));
        }
        $view = [
            'title' => __('Edit Activity Submission'),
            'breadcrumbs' => [
                route('admin.activity.index') => __('Activity Submission'),
                null => __('Edit')
            ],
            'schools' => School::orderBy('name', 'asc')->pluck('name', 'id')->toArray(),
            'types' => $this->types,
            'statuses' => $this->statuses,
            'data' => $activity
        ];
        return view('admin.activity.edit', $view);
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
        if ( ! auth()->guard('admin')->user()->can('update ' . $this->table)) {
            return redirect()->route('admin.activity.index')->with('alert-danger', __($this->noPermission));
        }
        $request->merge([
            'date' => date('Y-m-d', strtotime($request->date)),
        ]);
        if ($request->until_date) {
            $request->merge([
                'until_date' => date('Y-m-d', strtotime($request->until_date)),
            ]);
        }
        $activity->fill($request->all());
        $activity->submission_letter = $this->uploadSubmissionLetter($activity, $request, $activity->submission_letter);
        $activity->participant = $this->uploadParticipant($activity, $request, $activity->participant);
        $activity->save();
        $this->savePic($activity, $request);
        return redirect(url()->previous())->with('alert-success', __($this->updatedMessage));    
    }

    /**
     * Upload participant
     * 
     * @param  \App\activity  $activity
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
     * @param  \App\activity  $activity
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
     * @param  \App\activity  $activity
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
     * Approve activity
     * 
     * @param  \Illuminate\Http\Request  $request
     */
    public function approve(Request $request)
    {
        if ($request->ajax()) {
            if ( ! auth()->guard('admin')->user()->can('approval ' . $this->table)) {
                return response()->json(['status' => false, 'message' => __($this->noPermission)], 422);
            }
            event(new ActivityApproved($request));
            return response()->json(['status' => true, 'message' => __($this->updatedMessage)]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if ($request->ajax()) {
            if ( ! auth()->guard('admin')->user()->can('delete ' . $this->table)) {
                return response()->json(['status' => false, 'message' => __($this->noPermission)], 422);
            }
            Activity::destroy($request->selectedData);
            return response()->json(['status' => true, 'message' => __($this->deletedMessage)]);
        }
    }
}
