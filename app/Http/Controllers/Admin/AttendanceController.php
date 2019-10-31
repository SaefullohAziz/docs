<?php

namespace App\Http\Controllers\Admin;

use App\Attendance;
use App\School;
use App\Status;
use App\Teacher;
use Illuminate\Http\Request;
use App\Events\AttendanceProcessed;
use App\Events\AttendanceApproved;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAttendance;
use DataTables;
use App\Exports\AttendancesExport;

class AttendanceController extends Controller
{
    private $table;
    private $types;
    private $participants;
    private $participantPositions;
    private $transportations;
    private $arrivalPoints;

    /**
     * Create a new class instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth:admin');
        $this->table = 'attendances';
        $this->types = [
            'Audiensi' => 'Audiensi',
            'Visitasi' => 'Visitasi'
        ];
        $this->participants = [
            'Kepala Sekolah' => 'Kepala Sekolah', 'Wakil Kepala Sekolah' => 'Wakil Kepala Sekolah', 'Guru Produktif' => 'Guru Produktif', 'Guru Adaptif / Normatif' => 'Guru Adaptif / Normatif', 'Staff IT' => 'Staff IT', 'Lain-Lain' => 'Lain-Lain'
        ];
        $this->participantPositions = [
            'Kepala Sekolah' => 'Kepala Sekolah', 'Wakil Kepala Sekolah' => 'Wakil Kepala Sekolah', 'Kajur / Kaprodi' => 'Kajur / Kaprodi', 'Guru Produktif' => 'Guru Produktif', 'Lain-Lain' => 'Lain-Lain'
        ];
        $this->transportations = [
            'Kendaraan Pribadi / Sekolah / Yayasan' => 'Kendaraan Pribadi / Sekolah / Yayasan', 'Bus / Travel', 'Kereta Api' => 'Bus / Travel', 'Kereta Api', 'Pesawat' => 'Pesawat', 'Kapal Laut' => 'Kapal Laut', 'Lain-Lain' => 'Lain-Lain'
        ];
        $this->arrivalPoints = [
            'Terminal' => 'Terminal', 'Pool Travel' => 'Pool Travel', 'Airport' => 'Airport', 'Stasiun' => 'Stasiun', 'Lokasi Audiensi' => 'Lokasi Audiensi', 'Lain-Lain' => 'Lain-Lain'
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
            'title' => __('Attendance Confirmation'),
            'breadcrumbs' => [
                route('admin.attendance.index') => __('Attendance Confirmation'),
                null => __('Data')
            ],
            'schools' => School::orderBy('name', 'asc')->pluck('name', 'id')->toArray(),
            'types' => $this->types,
            'statuses' => Status::byNames(['Created', 'Processed', 'Approved', 'Sent', 'Refunded'])->pluck('name', 'id')->toArray(),
        ];
        return view('admin.attendance.index', $view);
    }

    /**
     * Display a listing of the deleted resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function bin()
    {
        if ( ! auth()->guard('admin')->user()->can('bin ' . $this->table)) {
            return redirect()->route('admin.attendance.index')->with('alert-danger', __($this->noPermission));
        }
        $view = [
            'title' => __('Deleted Attendance Confirmation'),
            'breadcrumbs' => [
                route('admin.attendance.index') => __('Deleted Attendance Confirmation'),
                null => __('Bin')
            ],
            'subtitle' => __('Bin')
        ];
        return view('admin.attendance.bin', $view);
    }

    /**
     * Show a listing of the resource for datatable.
     * 
     * @param  \Illuminate\Http\Request  $request
     */
    public function list(Request $request)
    {
        if ($request->ajax()) {
            $attendances = Attendance::list($request);
            return DataTables::of($attendances)
                ->addColumn('DT_RowIndex', function ($data) {
                    return '<div class="checkbox icheck"><label><input type="checkbox" name="selectedData[]" value="'.$data->id.'"></label></div>';
                })
                ->editColumn('created_at', function($data) {
                    return (date('d-m-Y h:m:s', strtotime($data->created_at)));
                })
                ->editColumn('submission_letter', function($data) {
                    return '<a href="'.route('download', ['dir' => encrypt('attendance/submission-letter'), 'file' => encrypt($data->submission_letter)]).'" class="btn btn-sm btn-success '.( ! isset($data->submission_letter)?'disabled':'').'" title="'.__('Download').'" target="_blank"><i class="fa fa-file"></i>  '.__('Download').'</a>';
                })
                ->editColumn('status', function($data) {
                    return $data->status.' by '.$data->status_by;
                })
                ->addColumn('action', function($data) {
                    return '<a class="btn btn-sm btn-success" href="'.route('admin.attendance.show', $data->id).'" title="'.__("See detail").'"><i class="fa fa-eye"></i> '.__("See").'</a> <a class="btn btn-sm btn-warning" href="'.route('admin.attendance.edit', $data->id).'" title="'.__("Edit").'"><i class="fa fa-edit"></i> '.__("Edit").'</a>';
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
            return redirect()->route('admin.attendance.index')->with('alert-danger', __($this->noPermission));
        }
        if (auth()->guard('admin')->user()->cant('adminCreate', Attendance::class)) {
            return redirect()->route('admin.attendance.index')->with('alert-danger', __($this->unauthorizedMessage));
        }
        $view = [
            'title' => __('Create Attendance Confirmation'),
            'breadcrumbs' => [
                route('admin.attendance.index') => __('Attendance Confirmation'),
                null => __('Create')
            ],
            'schools' => School::orderBy('name', 'asc')->pluck('name', 'id')->toArray(),
            'types' => $this->types,
            'participants' => $this->participants,
            'participantPositions' => $this->participantPositions,
            'transportations' => $this->transportations,
            'arrivalPoints' => $this->arrivalPoints,
            'destinations' => School::has('visitationDestination')->orderBy('name', 'asc')->pluck('name', 'name')->toArray(),
        ];
        return view('admin.attendance.create', $view);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAttendance $request)
    {
        if ( ! auth()->guard('admin')->user()->can('create ' . $this->table)) {
            return redirect()->route('admin.attendance.index')->with('alert-danger', __($this->noPermission));
        }
        if (auth()->guard('admin')->user()->cant('adminCreate', Attendance::class)) {
            return redirect()->route('admin.attendance.index')->with('alert-danger', __($this->unauthorizedMessage));
        }
        $request->merge([
            'participant' => ! empty($request->participant)?implode(', ', $request->participant):null,
            'date' => ($request->type='Audiensi'?date('Y-m-d', strtotime($request->date)):null),
            'until_date' => ($request->type=='Audiensi'?date('Y-m-d', strtotime($request->until_date)):null),
        ]);
        if ($request->type == 'Audiensi') {
            $teacher = Teacher::find($request->contact_person);
            $request->request->add([
                'contact_person_phone_number' => $teacher->phone_number,
            ]);
            $request->merge([
                'contact_person' => $teacher->name,
            ]);
        }
        $attendance = Attendance::create($request->except(['select_participant', 'participant_id', 'submit']));
        $attendance->submission_letter = $this->uploadSubmissionLetter($attendance, $request);
        $attendance->save();
        $this->saveParticipant($attendance, $request);
        return redirect(url()->previous())->with('alert-success', __($this->createdMessage));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function show(Attendance $attendance)
    {
        if ( ! auth()->guard('admin')->user()->can('read ' . $this->table)) {
            return redirect()->route('admin.attendance.index')->with('alert-danger', __($this->noPermission));
        }
        $view = [
            'title' => __('Attendance Confirmation Detail'),
            'breadcrumbs' => [
                route('admin.attendance.index') => __('Attendance Confirmation'),
                null => __('Detail')
            ],
            'schools' => School::orderBy('name', 'asc')->pluck('name', 'id')->toArray(),
            'types' => $this->types,
            'participants' => $this->participants,
            'participantPositions' => $this->participantPositions,
            'transportations' => $this->transportations,
            'arrivalPoints' => $this->arrivalPoints,
            'destinations' => School::has('visitationDestination')->orderBy('name', 'asc')->pluck('name', 'name')->toArray(),
            'contactPersons' => Teacher::whereHas('audience', function ($query) use ($attendance) {
                $query->where('attendances.id', $attendance->id);
            })->orderBy('name', 'asc')->pluck('name', 'id')->toArray(),
            'contactPerson' => Teacher::whereHas('audience', function ($query) use ($attendance) {
                $query->where('attendances.id', $attendance->id);
            })->where('name', $attendance->contact_person)->first(),
            'data' => $attendance
        ];
        return view('admin.attendance.show', $view);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function edit(Attendance $attendance)
    {
        if ( ! auth()->guard('admin')->user()->can('update ' . $this->table)) {
            return redirect()->route('admin.attendance.index')->with('alert-danger', __($this->noPermission));
        }
        $view = [
            'title' => __('Edit Attendance Confirmation'),
            'breadcrumbs' => [
                route('admin.attendance.index') => __('Attendance Confirmation'),
                null => __('Edit')
            ],
            'schools' => School::orderBy('name', 'asc')->pluck('name', 'id')->toArray(),
            'types' => $this->types,
            'participants' => $this->participants,
            'participantPositions' => $this->participantPositions,
            'transportations' => $this->transportations,
            'arrivalPoints' => $this->arrivalPoints,
            'destinations' => School::has('visitationDestination')->orderBy('name', 'asc')->pluck('name', 'name')->toArray(),
            'contactPersons' => Teacher::whereHas('audience', function ($query) use ($attendance) {
                $query->where('attendances.id', $attendance->id);
            })->orderBy('name', 'asc')->pluck('name', 'id')->toArray(),
            'contactPerson' => Teacher::whereHas('audience', function ($query) use ($attendance) {
                $query->where('attendances.id', $attendance->id);
            })->where('name', $attendance->contact_person)->first(),
            'data' => $attendance
        ];
        return view('admin.attendance.edit', $view);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function update(StoreAttendance $request, Attendance $attendance)
    {
        if ( ! auth()->guard('admin')->user()->can('update ' . $this->table)) {
            return redirect()->route('admin.attendance.index')->with('alert-danger', __($this->noPermission));
        }
        $request->merge([
            'participant' => ! empty($request->participant)?implode(', ', $request->participant):null,
            'date' => ($request->type='Audiensi'?date('Y-m-d', strtotime($request->date)):null),
            'until_date' => ($request->type=='Audiensi'?date('Y-m-d', strtotime($request->until_date)):null),
        ]);
        if ($request->type == 'Audiensi') {
            $teacher = Teacher::find($request->contact_person);
            $request->request->add([
                'contact_person_phone_number' => $teacher->phone_number,
            ]);
            $request->merge([
                'contact_person' => $teacher->name,
            ]);
        }
        $attendance = $attendance->fill($request->except(['select_participant', 'participant_id', 'submit']));
        $attendance->submission_letter = $this->uploadSubmissionLetter($attendance, $request, $attendance->submission_letter);
        $attendance->save();
        $this->saveParticipant($attendance, $request);
        return redirect(url()->previous())->with('alert-success', __($this->updatedMessage));
    }

    /**
     * Save participant
     * 
     * @param  \App\Attendance  $attendance
     * @param  \Illuminate\Http\Request  $request
     */
    public function saveParticipant($attendance, Request $request)
    {
        if ($request->isMethod('put')) {
            $attendance->participants()->detach();
        }
        if ($request->filled('participant_id')) {
            for ($i=0; $i < count($request->participant_id); $i++) { 
                $attendance->participants()->attach($request->participant_id[$i], [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    /**
     * Upload submission letter
     * 
     * @param  \App\Attendance  $attendance
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $oldFile
     * @return string
     */
    public function uploadSubmissionLetter($attendance, Request $request, $oldFile = 'null')
    {
        if ($request->hasFile('submission_letter')) {
            $filename = 'submission-letter-'.date('d-m-y-h-m-s-').md5(uniqid(rand(), true)).'.'.$request->submission_letter->extension();
            $path = $request->submission_letter->storeAs('public/attendance/submission-letter/'.$attendance->id, $filename);
            return $attendance->id.'/'.$filename;
        }
        return $oldFile;
    }

    /**
     * Process data
     * 
     * @param  \Illuminate\Http\Request  $request
     */
    public function process(Request $request)
    {
        if ($request->ajax()) {
            if ( ! auth()->guard('admin')->user()->can('approval ' . $this->table)) {
                return response()->json(['status' => false, 'message' => __($this->noPermission)], 422);
            }
            event(new AttendanceProcessed($request));
            return response()->json(['status' => true, 'message' => __($this->updatedMessage)]);
        }
    }

    /**
     * Approve data
     * 
     * @param  \Illuminate\Http\Request  $request
     */
    public function approve(Request $request)
    {
        if ($request->ajax()) {
            if ( ! auth()->guard('admin')->user()->can('approval ' . $this->table)) {
                return response()->json(['status' => false, 'message' => __($this->noPermission)], 422);
            }
            event(new AttendanceApproved($request));
            return response()->json(['status' => true, 'message' => __($this->updatedMessage)]);
        }
    }

    /**
     * Export listing as Excel
     * 
     * @param  \Illuminate\Http\Request  $request
     */
    public function export(Request $request)
    {
        return (new AttendancesExport($request))->download('attendance-'.date('d-m-Y-h-m-s').'.xlsx');
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
        Attendance::destroy($request->selectedData);
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
        Attendance::onlyTrashed()->whereIn('id', $request->selectedData)->restore();
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
        Attendance::onlyTrashed()->whereIn('id', $request->selectedData)->forceDelete();
        return response()->json(['status' => true, 'message' => __($this->deletedMessage)]);
    }
}
