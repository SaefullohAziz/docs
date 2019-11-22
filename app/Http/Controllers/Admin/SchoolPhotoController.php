<?php

namespace App\Http\Controllers\Admin;

use App\School;
use App\SchoolPhoto;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSchoolPhoto;
use Spatie\Image\Image;
use Spatie\Image\Manipulations;

class SchoolPhotoController extends Controller
{
    private $table;

    /**
     * Create a new class instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->table = 'school_photos';
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Filter resource
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\School  $school
     * @return \Illuminate\Http\Response
     */
    public function filter(Request $request, School $school, $token = null)
    {
        return redirect(url()->previous() . '#school-photos')->with('photoCategory', base64_decode($token));
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
     * @param  \App\School  $school
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSchoolPhoto $request, School $school)
    {
        if ($request->ajax()) {
            for ($i=0; $i < count($request->photos); $i++) { 
                $schoolPhoto = $school->photo()->create([
                    'category' => $request->category,
                    'name' => $this->uploadPhoto($school, $request->photos[$i]),
                    'description' => $request->description[$i]
                ]);
            }
            return response()->json(['status' => true]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\SchoolPhoto  $schoolPhoto
     * @return \Illuminate\Http\Response
     */
    public function show(SchoolPhoto $schoolPhoto)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\SchoolPhoto  $schoolPhoto
     * @return \Illuminate\Http\Response
     */
    public function edit(SchoolPhoto $schoolPhoto)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\SchoolPhoto  $schoolPhoto
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SchoolPhoto $schoolPhoto)
    {
        //
    }

    /**
     * Upload photo for school
     * 
     * @param  \App\School  $school
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $oldFile
     * @return string
     */
    public function uploadPhoto($school, $photo)
    {
        Image::load($photo)
                    ->fit(Manipulations::FIT_CROP, 1280, 720)
                    ->optimize()
                    ->save();
        $filename = 'photo_'.date('d_m_Y_H_i_s_').md5(uniqid(rand(), true)).'.'.$photo->extension();
        $path = $photo->storeAs('public/school/photo/'.$school->id, $filename);
        return $school->id.'/'.$filename;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        foreach ($request->selectedData as $id) {
            $photo = SchoolPhoto::find($id);
            unlink(storage_path('app/public/school/photo/'.$photo->name));
        }
        SchoolPhoto::destroy($request->selectedData);
        return response()->json(['status' => true, 'message' => __($this->deletedMessage)]);
    }
}
