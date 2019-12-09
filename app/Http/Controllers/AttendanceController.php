<?php

namespace App\Http\Controllers;

use App\Attendance;
use App\School;
use App\Status;
use App\Teacher;
use Illuminate\Http\Request;
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
        $view = [
            'title' => __('Attendance Confirmation'),
            'breadcrumbs' => [
                route('attendance.index') => __('Attendance Confirmation'),
                null => __('Data')
            ],
            'types' => $this->types,
            'statuses' => Status::byNames(['Created', 'Processed', 'Approved', 'Sent', 'Refunded'])->pluck('name', 'id')->toArray(),
        ];
        return view('attendance.index', $view);
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
                    return (date('d-m-Y H:i:s', strtotime($data->created_at)));
                })
                ->editColumn('submission_letter', function($data) {
                    return '<a href="'.route('download', ['dir' => encrypt('attendance/submission-letter'), 'file' => encrypt($data->submission_letter)]).'" class="btn btn-sm btn-success '.( ! isset($data->submission_letter)?'disabled':'').'" title="'.__('Download').'" target="_blank"><i class="fa fa-file"></i>  '.__('Download').'</a>';
                })
                ->editColumn('status', function($data) {
                    return $data->status;
                })
                ->addColumn('action', function($data) {
                    return '<a class="btn btn-sm btn-success" href="'.route('attendance.show', $data->id).'" title="'.__("See detail").'"><i class="fa fa-eye"></i> '.__("See").'</a> <a class="btn btn-sm btn-warning" href="'.route('attendance.edit', $data->id).'" title="'.__("Edit").'"><i class="fa fa-edit"></i> '.__("Edit").'</a>';
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
        if (auth()->user()->cant('create', Attendance::class)) {
            return redirect()->route('attendance.index')->with('alert-danger', __($this->unauthorizedMessage) . ' ' . __('Your school does not meet the requirements and / or has not added teachers to the system.'));
        }
        $view = [
            'title' => __('Create Attendance Confirmation'),
            'breadcrumbs' => [
                route('attendance.index') => __('Attendance Confirmation'),
                null => __('Create')
            ],
            'types' => $this->types,
            'participants' => $this->participants,
            'participantPositions' => $this->participantPositions,
            'transportations' => $this->transportations,
            'arrivalPoints' => $this->arrivalPoints,
            'destinations' => School::has('visitationDestinations')->pluck('name', 'name')->toArray(),
        ];
        return view('attendance.create', $view);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (auth()->user()->cant('create', Attendance::class)) {
            return redirect()->route('attendance.index')->with('alert-danger', __($this->unauthorizedMessage));
        }
        $request->merge([
            'school_id' => auth()->user()->school_id,
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
        $view = [
            'title' => __('Attendance Confirmation Detail'),
            'breadcrumbs' => [
                route('attendance.index') => __('Attendance Confirmation'),
                null => __('Detail')
            ],
            'schools' => School::pluck('name', 'id')->toArray(),
            'types' => $this->types,
            'participants' => $this->participants,
            'participantPositions' => $this->participantPositions,
            'transportations' => $this->transportations,
            'arrivalPoints' => $this->arrivalPoints,
            'destinations' => School::has('visitationDestinations')->pluck('name', 'name')->toArray(),
            'contactPersons' => Teacher::whereHas('audience', function ($query) use ($attendance) {
                $query->where('attendances.id', $attendance->id);
            })->orderBy('name', 'asc')->pluck('name', 'id')->toArray(),
            'contactPerson' => Teacher::whereHas('audience', function ($query) use ($attendance) {
                $query->where('attendances.id', $attendance->id);
            })->where('name', $attendance->contact_person)->first(),
            'data' => $attendance
        ];
        return view('attendance.show', $view);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function edit(Attendance $attendance)
    {
        $view = [
            'title' => __('Edit Attendance Confirmation'),
            'breadcrumbs' => [
                route('attendance.index') => __('Attendance Confirmation'),
                null => __('Edit')
            ],
            'schools' => School::pluck('name', 'id')->toArray(),
            'types' => $this->types,
            'participants' => $this->participants,
            'participantPositions' => $this->participantPositions,
            'transportations' => $this->transportations,
            'arrivalPoints' => $this->arrivalPoints,
            'destinations' => School::has('visitationDestinations')->pluck('name', 'name')->toArray(),
            'contactPersons' => Teacher::whereHas('audience', function ($query) use ($attendance) {
                $query->where('attendances.id', $attendance->id);
            })->orderBy('name', 'asc')->pluck('name', 'id')->toArray(),
            'contactPerson' => Teacher::whereHas('audience', function ($query) use ($attendance) {
                $query->where('attendances.id', $attendance->id);
            })->where('name', $attendance->contact_person)->first(),
            'data' => $attendance
        ];
        return view('attendance.edit', $view);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Attendance $attendance)
    {
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
     * Remove the specified resource from storage.
     *
     * @param  \App\Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function destroy(Attendance $attendance)
    {
        //
    }
}
