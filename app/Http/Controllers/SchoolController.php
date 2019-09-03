<?php

namespace App\Http\Controllers;

use Auth;
use App\Province;
use App\Regency;
use App\Department;
use App\School;
use App\Pic;
use App\SchoolLevel;
use App\SchoolStatus;
use Illuminate\Http\Request;
use App\Http\Requests\StoreSchool;
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
        $this->middleware('auth');
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
        $school = School::find(auth()->user()->school->id);
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
            'school' => $school
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
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
            'school' => $school
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
