<?php

namespace App\Http\Controllers;

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
use App\Events\TrainingRegistered;

class TrainingController extends Controller
{
    private $table;
    private $types;
    private $roomTypes;

    /**
     * Create a new class instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
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
        $view = [
            'title' => __('Training'),
            'breadcrumbs' => [
                route('training.index') => __('Training'),
                null => __('Data')
            ],
            'types' => $this->types,
            'statuses' => Status::byNames(['Processed', 'Canceled', 'Approved', 'Participant'])->pluck('name', 'id')->toArray(),
        ];
        return view('training.index', $view);
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
                    return (date('d-m-Y H:i:s', strtotime($data->created_at)));
                })
                ->editColumn('selection_result', function($data) {
                    $file = $data->selection_result;
                    if (strpos($file, '/') == false) {
                        $file = date('Y-m-d', strtotime($data->created_at)) . '/' . $file;
                    }
                    return '<a href="'.route('download', ['dir' => encrypt('training/selection-result'), 'file' => encrypt($file)]).'" class="btn btn-sm btn-success '.( ! isset($data->selection_result)?'disabled':'').'" title="'.__('Download').'" target="_blank"><i class="fa fa-file"></i>  '.__('Download').'</a>';
                })
                ->editColumn('approval_letter_of_commitment_fee', function($data) {
                    $file = $data->approval_letter_of_commitment_fee;
                    if (strpos($file, '/') == false) {
                        $file = date('Y-m-d', strtotime($data->created_at)) . '/' . $file;
                    }
                    return '<a href="'.route('download', ['dir' => encrypt('training/commitment-letter'), 'file' => encrypt($file)]).'" class="btn btn-sm btn-success '.( ! isset($data->approval_letter_of_commitment_fee)?'disabled':'').'" title="'.__('Download').'" target="_blank"><i class="fa fa-file"></i>  '.__('Download').'</a>';
                })
                ->editColumn('status', function($data) {
                    return $data->status;
                })
                ->addColumn('action', function($data) {
                    return '<a class="btn btn-sm btn-success" href="'.route('training.show', $data->id).'" title="'.__("See detail").'"><i class="fa fa-eye"></i> '.__("See").'</a> <a class="btn btn-sm btn-warning" href="'.route('training.edit', $data->id).'" title="'.__("Edit").'"><i class="fa fa-edit"></i> '.__("Edit").'</a>';
                })
                ->rawColumns(['DT_RowIndex', 'selection_result', 'approval_letter_of_commitment_fee', 'action'])
                ->make(true);
        }
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function preCreate(Request $request)
    {
        $request->validate([
            'type' => 'required',
        ]);
        if (auth()->user()->cant('preCreate', Training::class)) {
            return redirect()->route('training.create')->with('alert-danger', __($this->unauthorizedMessage) . ' ' . session('additionalMessage'));
        }
        return redirect()->route('training.create')->with('type', $request->type)->with('implementation', $request->implementation);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (auth()->user()->cant('create', Training::class)) {
            return redirect()->route('training.index')->with('alert-danger', __($this->unauthorizedMessage) . ' ' . __('Your school does not meet the requirements and / or do not have teachers.'));
        }
        $view = [
            'title' => __('Register Training'),
            'breadcrumbs' => [
                route('training.index') => __('Training'),
                null => __('Create')
            ],
            'types' => $this->types(),
            'implementations' => auth()->user()->school->implementedDepartments->pluck('name', 'abbreviation')->toArray(),
            'roomTypes' => $this->roomTypes,
            'participants' => Participant::bySchool(auth()->user()->school->id)->pluck('name', 'id')->toArray(),
            'setting' => json_decode(setting('training_settings')),
        ];

        if (session('type')) {
            $view = array_merge($view, ['type' => session('type'), 'implementation' => session('implementation')]);
            return view('training.create', $view);
        }
        return view('training.preCreate', $view);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTraining $request)
    {
        if (auth()->user()->cant('create', Training::class)) {
            return redirect()->route('training.index')->with('alert-danger', __($this->unauthorizedMessage));
        }
        if (auth()->user()->cant('preCreate', Training::class)) {
            return redirect()->route('training.create')->with('alert-danger', __($this->unauthorizedMessage) . ' ' . session('additionalMessage'));
        }
        $request->request->add([
            'school_id' => auth()->user()->school->id,
            'booking_code' => Str::random(12),
        ]);
        $request->merge([
            'room_type' => (empty($request->room_type)?null:implode(', ', $request->room_type))
        ]);
        $training = Training::create($request->all());
        $training->approval_letter_of_commitment_fee = $this->uploadCommitmentLetter($training, $request);
        $training->selection_result = $this->uploadSelectionResult($training, $request);
        $training->save();
        $this->saveParticipant($training, $request);
        $this->savePic($training, $request);
        event(new TrainingRegistered($training));
        return redirect()->route('payment.index')->with('alert-success', __($this->createdMessage) . ' ' . __('To complete this registration, please complete payment.') . ' ' . __('Please check your e-mail for more information.'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Training  $training
     * @return \Illuminate\Http\Response
     */
    public function show(Training $training)
    {
        if ( ! auth()->user()->can('view', $training)) {
            return redirect()->route('training.index')->with('alert-danger', __($this->noPermission));
        }
        $view = [
            'title' => __('Training Detail'),
            'breadcrumbs' => [
                route('training.index') => __('Training'),
                null => __('Detail')
            ],
            'types' => $this->types,
            'implementations' => auth()->user()->school->implementedDepartments->pluck('name', 'abbreviation')->toArray(),
            'roomTypes' => $this->roomTypes,
            'participants' => Participant::bySchool($training->school_id)->pluck('name', 'id')->toArray(),
            'training' => $training,
        ];
        return view('training.show', $view);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Training  $training
     * @return \Illuminate\Http\Response
     */
    public function edit(Training $training)
    {
        if (auth()->user()->cant('update', $training)) {
            return redirect()->route('training.index')->with('alert-danger', __($this->noPermission));
        }
        $view = [
            'title' => __('Edit Training'),
            'breadcrumbs' => [
                route('training.index') => __('Training'),
                null => __('Edit')
            ],
            'types' => $this->types,
            'implementations' => auth()->user()->school->implementedDepartments->pluck('name', 'abbreviation')->toArray(),
            'roomTypes' => $this->roomTypes,
            'participants' => Participant::bySchool($training->school_id)->pluck('name', 'id')->toArray(),
            'training' => $training,
        ];
        return view('training.edit', $view);
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
        if (auth()->user()->cant('update', $training)) {
            return redirect()->route('training.index')->with('alert-danger', __($this->noPermission));
        }
        $request->request->add([
            'school_id' => auth()->user()->school->id,
            'booking_code' => Str::random(12),
        ]);
        $request->merge([
            'room_type' => (empty($request->room_type)?null:implode(', ', $request->room_type))
        ]);
        $training->fill($request->all());
        $training->approval_letter_of_commitment_fee = $this->uploadCommitmentLetter($training, $request, $training->approval_letter_of_commitment_fee);
        $training->selection_result = $this->uploadSelectionResult($training, $request, $training->selection_result);
        $training->save();
        $this->saveParticipant($training, $request);
        $this->savePic($training, $request);
        return redirect(url()->previous())->with('alert-success', __($this->createdMessage));
    }

    /**
     * Show filtered training type
     *
     * @return void
     */
    public function types()
    {
        $settings = collect(json_decode(setting('training_settings')))->filter(function ($value, $key) {
            return setting($value->status_slug) == 1;
        });
        $trainingTypes = $settings->mapWithKeys(function ($item) {
            return [$item->name => $item->name];
        })->toArray();
        return $trainingTypes;
    }

    /**
     * Upload commitment letter
     * 
     * @param  \App\Training  $training
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $oldFile
     * @return string
     */
    public function uploadCommitmentLetter($training, $request, $oldFile = null)
    {
        if ($request->hasFile('approval_letter_of_commitment_fee')) {
            $filename = 'approval_letter_of_commitment_fee_'.date('d_m_Y_H_i_s_').md5(uniqid(rand(), true)).'.'.$request->approval_letter_of_commitment_fee->extension();
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
    public function uploadSelectionResult($training, $request, $oldFile = null)
    {
        if ($request->hasFile('selection_result')) {
            $filename = 'selection_result_'.date('d_m_Y_H_i_s_').md5(uniqid(rand(), true)).'.'.$request->selection_result->extension();
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
    public function saveParticipant($training, $request)
    {
        if ($request->filled('participant_id')) {
            $training->participants()->sync($request->participant_id);
        }
    }

    /**
     * Save pic
     * 
     * @param  \App\Training  $training
     * @param  \Illuminate\Http\Request  $request
     */
    public function savePic($training, $request)
    {
        $pic = Pic::bySchool($training->school_id)->first();
        if ($request->isMethod('put')) {
            $schoolPic = Pic::bySchool($training->school_id)->where('id', $training->trainingPic->pic->id)->first();
            if ( ! $schoolPic) {
                Pic::destroy($training->trainingPic->pic->id);
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
        $training->pic()->sync([$pic->id]);
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
        Training::destroy($request->selectedData);
        return response()->json(['status' => true, 'message' => __($this->deletedMessage)]);
    }
}
