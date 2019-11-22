<?php

namespace App\Http\Controllers;

use Auth;
use App\Province;
use App\Regency;
use App\Department;
use App\PoliceNumber;
use App\School;
use App\Pic;
use App\Document;
use App\SchoolPhoto;
use App\SchoolLevel;
use App\SchoolStatus;
use Illuminate\Http\Request;
use App\Http\Requests\StoreSchool;
use App\Http\Requests\StoreJoinedSchoolSet;
use Validator;
use Spatie\Image\Image;
use Spatie\Image\Manipulations;

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
        $school = School::where('id', auth()->user()->school->id)->with(['documents' => function ($query) {
            $query->when(session('documentCategory'), function ($subQuery) {
                $subQuery->where('documents.category', session('documentCategory'));
            });
        }, 'photos' => function ($query) {
            $query->when(session('photoCategory'), function ($subQuery) {
                $subQuery->where('school_photos.category', session('photoCategory'));
            });
        }])->first();
        $view = [
            'title' => __('School Detail'),
            'breadcrumbs' => [
                route('school.index') => __('School'),
                null => __('Detail')
            ],
            'provinces' => Province::pluck('name', 'name')->toArray(),
            'regencies' => Regency::getByProvinceName($school->province)->pluck('name', 'name')->toArray(),
            'policeNumbers' => PoliceNumber::pluck('name', 'name')->toArray(),
            'departments' => array_merge(Department::pluck('name')->toArray(), [__('Other')]),
            'isoCertificates' => $this->isoCertificates,
            'references' => $this->references,
            'data' => $school,
            'documentCategories' => [
                'Update Dokumen Persyaratan' => 'Update Dokumen Persyaratan', 
                'Form Aplikasi & Komitmen' => 'Form Aplikasi & Komitmen', 
                'MikroTik Academy' => 'MikroTik Academy'
            ],
            'photoCategories' => [
                'Kegiatan' => 'Kegiatan',
                'Dokumentasi' => 'Dokumentasi'
            ],
        ];
        return view('school.show', $view);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (setting('school_form_status') == 0) {
            return redirect()->route('home')->with('alert-danger', __($this->unauthorizedMessage));
        }
        $view = [
            'title' => __('Register School'),
            'provinces' => Province::pluck('name', 'name')->toArray(),
            'regencies' => Regency::pluck('name', 'name')->toArray(),
            'policeNumbers' => PoliceNumber::pluck('name', 'name')->toArray(),
            'departments' => array_merge(Department::pluck('name')->toArray(), [__('Other')]),
            'isoCertificates' => $this->isoCertificates,
            'references' => $this->references
        ];
        return view('school.register', $view);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSchool $request)
    {
        $request->merge([
            'department' => implode(', ', $request->department),
            'reference' => implode(', ', $request->reference),
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
        $school->pic()->attach($pic->id);
        $status = SchoolStatus::byName('Daftar')->first();
        $school->statuses()->attach($status->id, [
            'created_by' => 'school',
        ]);
        $this->uploadPhoto($school, $request);
        return redirect(url()->previous())->with('alert-success', __('Thank you! Your registration has been successful. Please check your email for the next step.'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\School  $school
     * @return \Illuminate\Http\Response
     */
    public function show(School $school)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\School  $school
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        $school = School::find(auth()->user()->school->id);
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
            'data' => $school
        ];
        return view('school.edit', $view);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\School  $school
     * @return \Illuminate\Http\Response
     */
    public function update(StoreSchool $request)
    {
        $school = School::find(auth()->user()->school->id);
        $request->merge([
            'department' => implode(', ', $request->department),
            'reference' => implode(', ', $request->reference),
        ]);
        $school = $school->fill($request->all());
        $school->document = $this->uploadDocument($school, $request);
        $school->save();
        $pic = $school->pic[0]->fill([
            'name' => $request->pic[0]['name'],
            'position' => $request->pic[0]['position'],
            'phone_number' => $request->pic[0]['phone_number'],
            'email' => $request->pic[0]['email']
        ]);
        $pic->save();
        if ($school->pic()->count() > 1) {
            $pic = $school->pic[1]->fill([
                'name' => $request->pic[1]['name'],
                'position' => $request->pic[1]['position'],
                'phone_number' => $request->pic[1]['phone_number'],
                'email' => $request->pic[1]['email']
            ]);
            $pic->save();
        }
        $this->uploadPhoto($school, $request);
        return redirect(url()->previous())->with('alert-success', __($this->updatedMessage));
    }

    /**
     * Show page for set implemented departmend and second PIC
     */
    public function set()
    {
        if (auth()->user()->cant('set', School::class)) {
            return redirect()->route('home')->with('alert-danger', __($this->unauthorizedMessage));
        }
        $view = [
            'title' => __('Requirements for Join'),
            'breadcrumbs' => [
                route('admin.school.index') => __('School'),
                null => __('Set')
            ],
            'departments' => array_merge(Department::where('name', '!=', 'Lain-Lain')->orderBy('name', 'asc')->pluck('name', 'id')->toArray(), ['Lain-Lain' => __('Etc')]),
        ];
        return view('school.set', $view);
    }

    /**
     * Save implemented department and second PIC into database
     */
    public function setStore(StoreJoinedSchoolSet $request)
    {
        if (auth()->user()->cant('set', School::class)) {
            return redirect()->route('home')->with('alert-danger', __($this->unauthorizedMessage));
        }
        $school = School::find(auth()->user()->school->id);
        $school->implementations()->delete();
        $department = Department::find($request->department);
        if ( ! $department) {
            $department = Department::firstOrCreate([
                'name' => $request->other_department, 
                'abbreviation' => $request->other_department
            ]);
        }
        $pic = Pic::create([
            'name' => $request->name,
            'position' => $request->position,
            'phone_number' => $request->phone_number,
            'email' => $request->email
        ]);
        $school->pic()->attach($pic->id);
        $school->implementations()->create(['department_id' => $department->id]);
        return redirect()->route('home')->with('alert-success', __($this->updatedMessage));
    }

     /**
     * Upload document for school
     * 
     * @param  \App\School  $school
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $oldFile
     * @return string
     */
    public function uploadDocument($school, Request $request, $oldFile = null)
    {
        if ($request->hasFile('document')) {
            $filename = 'document_'.date('d_m_Y_H_i_s_').md5(uniqid(rand(), true)).'.'.$request->document->extension();
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
                $filename = 'photo_'.date('d_m_Y_H_i_s_').md5(uniqid(rand(), true)).'.'.$photo->extension();
                $path = $photo->storeAs('public/school/photo/'.$school->id, $filename);
                $school->photo()->create([
                    'name' => $school->id.'/'.$filename,
                ]);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\School  $school
     * @return \Illuminate\Http\Response
     */
    public function destroy(School $school)
    {
        //
    }
}
