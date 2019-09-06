<?php

namespace App\Http\Controllers\Admin;

use Auth;
use App\Province;
use App\Regency;
use App\Department;
use App\PoliceNumber;
use App\School;
use App\Pic;
use App\SchoolLevel;
use App\SchoolStatus;
use App\Document;
use App\SchoolPhoto;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSchool;
use DataTables;
use Validator;
use App\Exports\SchoolsExport;
use Spatie\Image\Image;
use Spatie\Image\Manipulations;
use Illuminate\Support\Str;

class SchoolController extends Controller
{
    private $table;
    private $isoCertificates;
    private $references;

    /**
     * Create a new class instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth:admin');
        $this->table = 'schools';
        $this->isoCertificates = ['Sudah' => 'Sudah', 'Dalam Proses (persiapan dokumen / pembentukan team audit internal / pendampingan)' => 'Dalam Proses (persiapan dokumen / pembentukan team audit internal / pendampingan)', 'Belum' => 'Belum'];
        $this->references = ['Sekolah Peserta / Sekolah Binaan', 'Dealer', 'Internet (Facebook Page/Web)', 'Lain-Lain'];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if ( ! Auth::guard('admin')->user()->can('access ' . $this->table)) {
            return redirect()->route('admin.home')->with('alert-danger', $this->noPermission);
        }
        $view = [
            'title' => __('School'),
            'breadcrumbs' => [
                route('admin.school.index') => __('School'),
                null => __('Data')
            ],
            'provinces' => Province::pluck('name', 'name')->toArray(),
            'levels' => SchoolLevel::pluck('name', 'id')->toArray(),
        ];
        return view('admin.school.index', $view);
    }

    /**
     * Show a listing of the resource for datatable.
     * 
     * @param  \Illuminate\Http\Request  $request
     */
    public function list(Request $request)
    {
        if ($request->ajax()) {
            $schools = School::list($request);
            return DataTables::of($schools)
                ->addColumn('DT_RowIndex', function ($data) {
                    return '<div class="checkbox icheck"><label><input type="checkbox" name="selectedData[]" value="'.$data->id.'"></label></div>';
                })
                ->editColumn('created_at', function($data) {
                    return (date('d-m-Y h:m:s', strtotime($data->created_at)));
                })
                ->addColumn('action', function($data) {
                    return '<a class="btn btn-sm btn-success" href="'.route('admin.school.show', $data->id).'" title="'.__("See detail").'"><i class="fa fa-eye"></i> '.__("See").'</a> <a class="btn btn-sm btn-warning" href="'.route('admin.school.edit', $data->id).'" title="'.__("Edit").'"><i class="fa fa-edit"></i> '.__("Edit").'</a>';
                })
                ->rawColumns(['DT_RowIndex', 'action'])
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
        if ( ! Auth::guard('admin')->user()->can('create ' . $this->table)) {
            return redirect()->route('admin.school.index')->with('alert-danger', $this->noPermission);
        }
        $view = [
            'title' => __('Create School'),
            'breadcrumbs' => [
                route('admin.school.index') => __('School'),
                null => __('Create')
            ],
            'provinces' => Province::pluck('name', 'name')->toArray(),
            'regencies' => Regency::pluck('name', 'name')->toArray(),
            'policeNumbers' => PoliceNumber::pluck('name', 'name')->toArray(),
            'departments' => array_merge(Department::pluck('name')->toArray(), [__('Other')]),
            'isoCertificates' => $this->isoCertificates,
            'references' => $this->references
        ];
        return view('admin.school.create', $view);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSchool $request)
    {
        if ( ! Auth::guard('admin')->user()->can('create ' . $this->table)) {
            return redirect()->route('admin.school.index')->with('alert-danger', $this->noPermission);
        }
        $request->merge([
            'department' => implode(', ', $request->department),
            'reference' => implode(', ', $request->reference),
            'code' => Str::random(10),
        ]);
        $school = School::create($request->all());
        $school->document = $this->uploadDocument($school, $request);
        $school->save();
        $pic = Pic::create([
            'name' => $request->pic_name,
            'position' => $request->pic_position,
            'phone_number' => $request->pic_phone_number,
            'email' => $request->pic_email
        ]);
        $school->pic()->attach($pic->id, [
            'created_at' => now(),
            'updated_at' => now()
        ]);
        $status = SchoolStatus::byName('Daftar')->first();
        $school->status()->attach($status->id, [
            'created_by' => 'staff',
            'staff_id' => Auth::guard('admin')->user()->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        $this->uploadPhoto($school, $request);
        return redirect(url()->previous())->with('alert-success', $this->createdMessage);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\School  $school
     * @return \Illuminate\Http\Response
     */
    public function show(School $school)
    {
        if ( ! Auth::guard('admin')->user()->can('read ' . $this->table)) {
            return redirect()->route('admin.school.index')->with('alert-danger', $this->noPermission);
        }
        $view = [
            'title' => __('School Detail'),
            'breadcrumbs' => [
                route('admin.school.index') => __('School'),
                null => __('Detail')
            ],
            'provinces' => Province::pluck('name', 'name')->toArray(),
            'regencies' => Regency::getByProvinceName($school->province)->pluck('name', 'name')->toArray(),
            'policeNumbers' => PoliceNumber::pluck('name', 'name')->toArray(),
            'departments' => array_merge(Department::pluck('name')->toArray(), [__('Other')]),
            'isoCertificates' => $this->isoCertificates,
            'references' => $this->references,
            'school' => $school,
            'documentCategories' => [
                'Update Dokumen Persyaratan' => 'Update Dokumen Persyaratan', 
                'Form Aplikasi & Komitmen' => 'Form Aplikasi & Komitmen', 
                'MikroTik Academy' => 'MikroTik Academy'
            ],
            'schoolDocuments' => $school->documents,
            'photoCategories' => [
                'Kegiatan' => 'Kegiatan',
                'Dokumentasi' => 'Dokumentasi'
            ],
            'schoolPhotos' => $school->photo,
        ];
        if (session('photoCategory')) {
            $addonView = [
                'schoolPhotos' => SchoolPhoto::where('school_id', $school->id)->where('category', session('photoCategory'))->get(),
            ];
            $view = array_merge($view, $addonView);
        } if (session('documentCategory')) {
            $addonView = [
                'schoolDocuments' => Document::where('school_id', $school->id)->where('category', session('documentCategory'))->get(),
            ];
            $view = array_merge($view, $addonView);
        }
        return view('admin.school.show', $view);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\School  $school
     * @return \Illuminate\Http\Response
     */
    public function edit(School $school)
    {
        if ( ! Auth::guard('admin')->user()->can('update ' . $this->table)) {
            return redirect()->route('admin.school.index')->with('alert-danger', $this->noPermission);
        }
        $view = [
            'title' => __('Edit School'),
            'breadcrumbs' => [
                route('admin.school.index') => __('School'),
                null => __('Edit')
            ],
            'provinces' => Province::pluck('name', 'name')->toArray(),
            'regencies' => Regency::getByProvinceName($school->province)->pluck('name', 'name')->toArray(),
            'policeNumbers' => PoliceNumber::pluck('name', 'name')->toArray(),
            'departments' => array_merge(Department::pluck('name')->toArray(), [__('Other')]),
            'isoCertificates' => $this->isoCertificates,
            'references' => $this->references,
            'school' => $school
        ];
        return view('admin.school.edit', $view);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\School  $school
     * @return \Illuminate\Http\Response
     */
    public function update(StoreSchool $request, School $school)
    {
        if ( ! Auth::guard('admin')->user()->can('update ' . $this->table)) {
            return redirect()->route('admin.school.index')->with('alert-danger', $this->noPermission);
        }
        $request->merge([
            'department' => implode(', ', $request->department),
            'reference' => implode(', ', $request->reference),
        ]);
        $school = $school->fill($request->all());
        $school->document = $this->uploadDocument($school, $request);
        $school->save();
        $pic = Pic::find($school->schoolPic->pic_id);
        $pic->fill([
            'name' => $request->pic_name,
            'position' => $request->pic_position,
            'phone_number' => $request->pic_phone_number,
            'email' => $request->pic_email
        ]);
        $pic->save();
        $school->pic()->sync([$pic->id]);
        $this->uploadPhoto($school, $request);
        return redirect(url()->previous())->with('alert-success', $this->updatedMessage);
    }

    /**
     * Upload document for school
     * 
     * @param  \App\School  $school
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $oldFile
     * @return string
     */
    public function uploadDocument($school, Request $request, $oldFile = 'null')
    {
        if ($request->hasFile('document')) {
            $filename = 'document_'.date('d_m_y_h_m_s_').md5(uniqid(rand(), true)).'.'.$request->document->extension();
            $path = $request->document->storeAs('public/school/document/'.$school->id, $filename);
            return $school->id.'/'.$filename;
        }
        return $oldFile;
    }

    /**
     * Upload photo for school
     * 
     * @param  \App\School  $school
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $oldFile
     * @return string
     */
    public function uploadPhoto($school, Request $request)
    {
        if ($request->hasFile('photos')) {
            foreach ($request->photos as $photo) {
                Image::load($photo)
                    ->fit(Manipulations::FIT_CROP, 1280, 720)
                    ->optimize()
                    ->save();
                $filename = 'photo_'.date('d_m_y_h_m_s_').md5(uniqid(rand(), true)).'.'.$photo->extension();
                $path = $photo->storeAs('public/school/photo/'.$school->id, $filename);
                $school->photo()->create([
                    'name' => $school->id.'/'.$filename,
                ]);
            }
        }
    }

    /**
     * Export school listing as Excel
     * 
     * @param  \Illuminate\Http\Request  $request
     */
    public function export(Request $request)
    {
        return (new SchoolsExport($request))->download('school-'.date('d-m-Y-h-m-s').'.xlsx');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if ( ! Auth::guard('admin')->user()->can('delete ' . $this->table)) {
            return response()->json(['status' => false, 'message' => $this->noPermission], 422);
        }
        School::destroy($request->selectedData);
        return response()->json(['status' => true, 'message' => $this->deletedMessage]);
    }
}
