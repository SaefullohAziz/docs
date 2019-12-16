<?php

namespace App\Http\Controllers\Admin;

use Auth;
use App\Training;
use App\School;
use App\Status;
use App\Pic;
use App\Teacher as Participant;
use Illuminate\Http\Request;
use App\Events\TrainingCanceled;
use App\Events\TrainingProcessed;
use App\Events\TrainingApproved;
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
    private $implementations;
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
            return redirect()->route('admin.home')->with('alert-danger', __($this->noPermission));
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
            'batches' => range(0, 100)
        ];
        return view('admin.training.index', $view);
    }

    /**
     * Display a listing of the deleted resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function bin()
    {
        if ( ! auth()->guard('admin')->user()->can('bin ' . $this->table)) {
            return redirect()->route('admin.training.index')->with('alert-danger', __($this->noPermission));
        }
        $view = [
            'back' => route('admin.training.index'),
            'title' => __('Deleted Training'),
            'breadcrumbs' => [
                route('admin.training.index') => __('Training'),
                null => __('Deleted')
            ],
        ];
        return view('admin.training.bin', $view);
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
                ->addColumn('DT_RowIndex', function ($data) {
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
                ->editColumn('school', function($data) {
                    return '<a href="' . route('admin.school.show', $data->school_id) . '" class="btn">'. $data->school .'</a>';
                })
                ->editColumn('status', function($data) {
                    return $data->status.' by '.$data->status_by;
                })
                ->addColumn('action', function($data) {
                    return '<a class="btn btn-sm btn-success" href="'.route('admin.training.show', $data->id).'" title="'.__("See detail").'"><i class="fa fa-eye"></i> '.__("See").'</a> <a class="btn btn-sm btn-warning" href="'.route('admin.training.edit', $data->id).'" title="'.__("Edit").'"><i class="fa fa-edit"></i> '.__("Edit").'</a>';
                })
                ->rawColumns(['DT_RowIndex', 'school', 'selection_result', 'approval_letter_of_commitment_fee', 'action'])
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
            return redirect()->route('admin.training.index')->with('alert-danger', __($this->noPermission));
        }
        if (auth()->guard('admin')->user()->cant('adminCreate', Training::class)) {
            return redirect()->route('admin.training.index')->with('alert-danger', __($this->unauthorizedMessage));
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
            return redirect()->route('admin.training.index')->with('alert-danger', __($this->noPermission));
        }
        if (auth()->guard('admin')->user()->cant('adminCreate', Training::class)) {
            return redirect()->route('admin.training.index')->with('alert-danger', __($this->unauthorizedMessage));
        }
        $request->request->add(['booking_code' => Str::random(12)]);
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
        return redirect()->route('admin.payment.index')->with('alert-success', __($this->createdMessage) . ' ' . __('To complete this registration, please complete payment.') . ' ' . __('Please check your e-mail for more information.'));
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
            return redirect()->route('admin.training.index')->with('alert-danger', __($this->noPermission));
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
            return redirect()->route('admin.training.index')->with('alert-danger', __($this->noPermission));
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
            return redirect()->route('admin.training.index')->with('alert-danger', __($this->noPermission));
        }
        $request->request->add(['booking_code' => Str::random(12)]);
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
    public function uploadSelectionResult($training, Request $request, $oldFile = null)
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
    public function saveParticipant($training, Request $request)
    {
        if ($request->isMethod('put')) {
            $training->participants()->detach();
        }
        if ($request->filled('participant_id')) {
            for ($i=0; $i < count($request->participant_id); $i++) { 
                $training->participants()->attach($request->participant_id[$i], [
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
     * Cancel data
     * 
     * @param  \Illuminate\Http\Request  $request
     */
    public function cancel(Request $request)
    {
        if ($request->ajax()) {
            if ( ! auth()->guard('admin')->user()->can('approval ' . $this->table)) {
                return response()->json(['status' => false, 'message' => __($this->noPermission)], 422);
            }
            event(new TrainingCanceled($request));
            return response()->json(['status' => true, 'message' => __($this->updatedMessage)]);
        }
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
            event(new TrainingProcessed($request));
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
            event(new TrainingApproved($request));
            return response()->json(['status' => true, 'message' => __($this->updatedMessage)]);
        }
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
            return response()->json(['status' => false, 'message' => __($this->noPermission)], 422);
        }
        Training::destroy($request->selectedData);
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
        Training::onlyTrashed()->whereIn('id', $request->selectedData)->restore();
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
        Training::onlyTrashed()->whereIn('id', $request->selectedData)->forceDelete();
        return response()->json(['status' => true, 'message' => __($this->deletedMessage)]);
    }
}
