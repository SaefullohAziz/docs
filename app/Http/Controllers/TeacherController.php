<?php

namespace App\Http\Controllers;

use Auth;
use App\SchoolLevel;
use App\School;
use App\Teacher;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTeacher;
use DataTables;
use App\Exports\TeachersExport;
use Spatie\Image\Image;
use Spatie\Image\Manipulations;

class TeacherController extends Controller
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
        $this->table = 'teachers';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         $view = [
            'title' => __('Teacher'),
            'breadcrumbs' => [
                route('teacher.index') => __('Teacher'),
                null => 'Data'
            ],
        ];
        return view('teacher.index', $view);
    }

    /**
     * Show a listing of the resource for datatable.
     * 
     * @param  \Illuminate\Http\Request  $request
     */
    public function list(Request $request)
    {
        if ($request->ajax()) {
            $teachers = Teacher::list($request);
            return DataTables::of($teachers)
                ->addColumn('DT_RowIndex', function ($data) {
                    return '<div class="checkbox icheck"><label><input type="checkbox" name="selectedData[]" value="'.$data->id.'"></label></div>';
                })
                ->editColumn('created_at', function ($data) {
                    return (date('d-m-Y h:m:s', strtotime($data->created_at)));
                })
                ->addColumn('action', function ($data) {
                    return '<a class="btn btn-sm btn-success" href="'.route('teacher.show', $data->id).'" title="'.__("See detail").'"><i class="fa fa-eye"></i> '.__("See").'</a> <a class="btn btn-sm btn-warning" href="'.route('teacher.edit', $data->id).'" title="'.__("Edit").'"><i class="fa fa-edit"></i> '.__("Edit").'</a>';
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
        $view = [
            'title' => __('Create Teacher'),
            'breadcrumbs' => [
                route('teacher.index') => __('Teacher'),
                null => __('Create')
            ]
        ];
        return view('teacher.create', $view);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->merge(['school_id' => Auth::user()->school_id]);
        $teacher = Teacher::create($request->except(['terms', 'submit']));
        $teacher->photo = $this->uploadPhoto($teacher, $request);
        $teacher->save();
        return redirect(url()->previous())->with('alert-success', __($this->createdMessage));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Teacher  $teacher
     * @return \Illuminate\Http\Response
     */
    public function show(Teacher $teacher)
    {
        $view = [
            'title' => __('Teacher Detail'),
            'breadcrumbs' => [
                route('teacher.index') => __('Teacher'),
                null => __('Detail')
            ],
            'schools' => School::orderBy('name', 'asc')->pluck('name', 'id')->toArray(),
            'data' => $teacher
        ];
        return view('teacher.show', $view);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Teacher  $teacher
     * @return \Illuminate\Http\Response
     */
    public function edit(Teacher $teacher)
    {
        $view = [
            'title' => __('Edit Teacher'),
            'breadcrumbs' => [
                route('teacher.index') => __('Teacher'),
                null => __('Edit')
            ],
            'schools' => School::orderBy('name', 'asc')->pluck('name', 'id')->toArray(),
            'data' => $teacher
        ];
        return view('teacher.edit', $view);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Teacher  $teacher
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Teacher $teacher)
    {
        $teacher->fill($request->except(['school_id','terms', 'submit']));
        $teacher->photo = $this->uploadPhoto($teacher, $request, $teacher->photo);
        $teacher->save();
        return redirect(url()->previous())->with('alert-success', __($this->updatedMessage));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        Teacher::destroy($request->selectedData);
        return response()->json(['status' => true, 'message' => __($this->deletedMessage)]);    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Teacher  $teacher
     * @return \Illuminate\Http\Response
     */
    // public function destroy(Teacher $teacher)
    // {
        //
    // }

    /**
     * Upload photo
     * 
     * @param  \App\Teacher  $teacher
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $oldFile
     * @return string
     */
    public function uploadPhoto($teacher, Request $request, $oldFile = 'default.png')
    {
        if ($request->hasFile('photo')) {
            Image::load($request->photo)
                ->fit(Manipulations::FIT_CROP, 150, 150)
                ->optimize()
                ->save();
            $filename = 'photo_'.date('d_m_y_h_m_s_').md5(uniqid(rand(), true)).'.'.$request->photo->extension();
            $path = $request->photo->storeAs('public/teacher/photo/'.$teacher->id, $filename);
            return $teacher->id.'/'.$filename;
        }
        return $oldFile;
    }

    /**
     * Export listing as Excel
     * 
     * @param  \Illuminate\Http\Request  $request
     */
    public function export(Request $request)
    {
        return (new TeachersExport($request))->download('teacher-'.date('d-m-Y-h-m-s').'.xlsx');
    }
}
