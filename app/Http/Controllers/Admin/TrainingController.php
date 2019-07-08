<?php

namespace App\Http\Controllers\Admin;

use Auth;
use App\Training;
use App\School;
use App\Status;
use App\Pic;
use App\Teacher as Participant;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTraining;
use Illuminate\Support\Str;
use DataTables;
use Validator;
use App\Exports\TrainingsExport;

class TrainingController extends Controller
{
    private $createdMessage;
    private $updatedMessage;
    private $deletedMessage;
    private $noPermission;
    private $table;
    private $types;
    private $implementations;
    private $roomTypes;

    /**
     * Create a new class instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
        $this->createdMessage = __('Data successfully created.');
        $this->updatedMessage = __('Data successfully updated.');
        $this->deletedMessage = __('Data successfully deleted.');
        $this->noPermission = __('You have no related permission.');
        $this->table = 'trainings';
        $this->types = [
            'Basic (ToT)' => 'Basic (ToT)', 
            'Microsoft Network Fundamental' => 'Microsoft Network Fundamental', 
            'MikroTik' => 'MikroTik', 
            'Seagate' => 'Seagate', 
            'IoT' => 'IoT', 
            'Dicoding' => 'Dicoding', 
            'LS-Cable' => 'LS-Cable', 
            'Surveillance' => 'Surveillance', 
            'Elektronika Dasar' => 'Elektronika Dasar', 
            'Adobe Photoshop' => 'Adobe Photoshop', 
            'Microsoft Software Fundamental' => 'Microsoft Software Fundamental', 
            'Starter Kit Klinik Komputer' => 'Starter Kit Klinik Komputer'
        ];
        $this->implementations = [
            'TKJ' => 'Teknik Komputer Jaringan', 
			'MM' => 'Multimedia', 
			'TEIn' => 'Teknik Elektronika Industri',
			'TAV' => 'Teknik Audio Video',
			'Lain-Lain' => 'Lain-Lain'
        ];
        $this->roomTypes = [
            1, 2, 3, 4
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
            return redirect()->route('admin.home')->with('alert-danger', $this->noPermission);
        }
        $view = [
            'title' => __('Training'),
            'breadcrumbs' => [
                route('admin.training.index') => __('Training'),
                null => __('Data')
            ],
            'schools' => School::pluck('name', 'id')->toArray(),
            'types' => $this->types,
            'statuses' => Status::byNames(['Processed', 'Canceled', 'Approved', 'Participant'])->pluck('name', 'id')->toArray(),
        ];
        return view('admin.training.index', $view);
    }

    /**
     * Show a listing of the resource for datatable.
     * 
     * @param  \Illuminate\Http\Request  $request
     */
    public function list(Request $request)
    {
        if ($request->ajax()) {
            $trainings = Training::list($request);
            return DataTables::of($trainings)
                ->addColumn('DT_RowIndex', function ($data)
                {
                    return '<div class="checkbox icheck"><label><input type="checkbox" name="selectedData[]" value="'.$data->id.'"></label></div>';
                })
                ->editColumn('created_at', function($data) {
                    return (date('d-m-Y h:m:s', strtotime($data->created_at)));
                })
                ->editColumn('selection_result', function($data) {
                    return '<a href="'.route('download', ['dir' => encrypt('training/selection-result'), 'file' => encrypt($data->selection_result)]).'" class="btn btn-sm btn-success '.( ! isset($data->selection_result)?'disabled':'').'" title="'.__('Download').'" target="_blank"><i class="fa fa-file"></i>  '.__('Download').'</a>';
                })
                ->editColumn('approval_letter_of_commitment_fee', function($data) {
                    return '<a href="'.route('download', ['dir' => encrypt('training/commitment-letter'), 'file' => encrypt($data->approval_letter_of_commitment_fee)]).'" class="btn btn-sm btn-success '.( ! isset($data->approval_letter_of_commitment_fee)?'disabled':'').'" title="'.__('Download').'" target="_blank"><i class="fa fa-file"></i>  '.__('Download').'</a>';
                })
                ->editColumn('status', function($data) {
                    return $data->status.' by '.$data->status_by;
                })
                ->addColumn('action', function($data) {
                    return '<a class="btn btn-sm btn-success" href="'.route('admin.training.show', $data->id).'" title="'.__("See detail").'"><i class="fa fa-eye"></i> '.__("See").'</a> <a class="btn btn-sm btn-warning" href="'.route('admin.training.edit', $data->id).'" title="'.__("Edit").'"><i class="fa fa-edit"></i> '.__("Edit").'</a>';
                })
                ->rawColumns(['DT_RowIndex', 'selection_result', 'approval_letter_of_commitment_fee', 'action'])
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
            return redirect()->route('admin.training.index')->with('alert-danger', $this->noPermission);
        }
        $view = [
            'title' => __('Register Training'),
            'breadcrumbs' => [
                route('admin.training.index') => __('Training'),
                null => __('Create')
            ],
            'schools' => School::pluck('name', 'id')->toArray(),
            'types' => $this->types,
            'implementations' => $this->implementations,
            'roomTypes' => $this->roomTypes,
        ];
        return view('admin.training.create', $view);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTraining $request)
    {
        if ( ! auth()->guard('admin')->user()->can('create ' . $this->table)) {
            return redirect()->route('admin.training.index')->with('alert-danger', $this->noPermission);
        }
        $request->request->add(['booking_code' => Str::random(12)]);
        $training = Training::create($request->all());
        $training->approval_letter_of_commitment_fee = $this->uploadCommitmentLetter($training, $request);
        $training->selection_result = $this->uploadSelectionResult($training, $request);
        $training->save();
        $this->saveParticipant($training, $request);
        $this->savePic($training, $request);
        $this->saveStatus($training, 'Created', 'Mendaftar program training.');
        return redirect(url()->previous())->with('alert-success', $this->createdMessage);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Training  $training
     * @return \Illuminate\Http\Response
     */
    public function show(Training $training)
    {
        if ( ! auth()->guard('admin')->user()->can('read ' . $this->table)) {
            return redirect()->route('admin.training.index')->with('alert-danger', $this->noPermission);
        }
        $view = [
            'title' => __('Training Detail'),
            'breadcrumbs' => [
                route('admin.training.index') => __('Training'),
                null => __('Detail')
            ],
            'schools' => School::pluck('name', 'id')->toArray(),
            'types' => $this->types,
            'implementations' => $this->implementations,
            'roomTypes' => $this->roomTypes,
            'participants' => Participant::bySchool($training->school_id)->pluck('name', 'id')->toArray(),
            'training' => $training,
        ];
        return view('admin.training.show', $view);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Training  $training
     * @return \Illuminate\Http\Response
     */
    public function edit(Training $training)
    {
        if ( ! auth()->guard('admin')->user()->can('update ' . $this->table)) {
            return redirect()->route('admin.training.index')->with('alert-danger', $this->noPermission);
        }
        $view = [
            'title' => __('Edit Training'),
            'breadcrumbs' => [
                route('admin.training.index') => __('Training'),
                null => __('Edit')
            ],
            'schools' => School::pluck('name', 'id')->toArray(),
            'types' => $this->types,
            'implementations' => $this->implementations,
            'roomTypes' => $this->roomTypes,
            'participants' => Participant::bySchool($training->school_id)->pluck('name', 'id')->toArray(),
            'training' => $training,
        ];
        return view('admin.training.edit', $view);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Training  $training
     * @return \Illuminate\Http\Response
     */
    public function update(StoreTraining $request, Training $training)
    {
        if ( ! auth()->guard('admin')->user()->can('update ' . $this->table)) {
            return redirect()->route('admin.training.index')->with('alert-danger', $this->noPermission);
        }
        $request->request->add(['booking_code' => Str::random(12)]);
        $training->fill($request->all());
        $training->approval_letter_of_commitment_fee = $this->uploadCommitmentLetter($training, $request, $training->approval_letter_of_commitment_fee);
        $training->selection_result = $this->uploadSelectionResult($training, $request, $training->selection_result);
        $training->save();
        $this->saveParticipant($training, $request);
        $this->savePic($training, $request);
        $this->saveStatus($training, 'Edited', 'Mengubah pendaftaran training.');
        return redirect(url()->previous())->with('alert-success', $this->createdMessage);
    }

    /**
     * Upload commitment letter
     * 
     * @param  \App\Training  $training
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $oldFile
     * @return string
     */
    public function uploadCommitmentLetter($training, Request $request, $oldFile = null)
    {
        if ($request->hasFile('approval_letter_of_commitment_fee')) {
            $filename = 'approval_letter_of_commitment_fee_'.date('d_m_y_h_m_s_').md5(uniqid(rand(), true)).'.'.$request->approval_letter_of_commitment_fee->extension();
            $path = $request->approval_letter_of_commitment_fee->storeAs('public/training/commitment-letter/'.$training->id, $filename);
            return $training->id.'/'.$filename;
        }
        return $oldFile;
    }

     /**
     * Upload report
     * 
     * @param  \App\Training  $training
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $oldFile
     * @return string
     */
    public function uploadSelectionResult($training, Request $request, $oldFile = null)
    {
        if ($request->hasFile('selection_result')) {
            $filename = 'selection_result_'.date('d_m_y_h_m_s_').md5(uniqid(rand(), true)).'.'.$request->selection_result->extension();
            $path = $request->selection_result->storeAs('public/training/selection-result/'.$training->id, $filename);
            return $training->id.'/'.$filename;
        }
        return $oldFile;
    }

    /**
     * Save participant
     * 
     * @param  \App\Training  $training
     * @param  \Illuminate\Http\Request  $request
     */
    public function saveParticipant($training, Request $request)
    {
        if ($request->isMethod('put')) {
            $training->participant()->detach();
        }
        if ($request->filled('participant_id')) {
            for ($i=0; $i < count($request->participant_id); $i++) { 
                $training->participant()->attach($request->participant_id[$i], [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    /**
     * Save pic
     * 
     * @param  \App\Training  $training
     * @param  \Illuminate\Http\Request  $request
     */
    public function savePic($training, Request $request)
    {
        $pic = Pic::bySchool($request->school_id)->first();
        if ($request->isMethod('put')) {
            $schoolPic = Pic::bySchool($training->school_id)->where('id', $training->trainingPic->pic->id)->first();
            if ( ! $schoolPic) {
                Pic::destroy($training->trainingPic->pic->id);
            }
            $training->pic()->detach();
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
        $training->pic()->attach($pic->id, [
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Save status
     * 
     * @param  \App\Training  $training
     * @param  string  $status
     * @param  string  $desc
     */
    public function saveStatus($training, $status, $desc)
    {
        $log = actlog($desc);
        $status = Status::byName($status)->first();
        $training->status()->attach($status->id, [
            'log_id' => $log,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Export training listing as Excel
     * 
     * @param  \Illuminate\Http\Request  $request
     */
    public function export(Request $request)
    {
        return (new TrainingsExport($request))->download('training-'.date('d-m-Y-h-m-s').'.xlsx');
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
            return response()->json(['status' => false, 'message' => $this->noPermission], 422);
        }
        Training::destroy($request->selectedData);
        return response()->json(['status' => true, 'message' => $this->deletedMessage]);
    }
}
