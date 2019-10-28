<?php

namespace App\Http\Controllers\Admin;

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
        $this->middleware('auth:admin');
        $this->table = 'teachers';
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
            'title' => __('Teacher'),
            'breadcrumbs' => [
                route('admin.teacher.index') => __('Teacher'),
                null => 'Data'
            ],
            'levels' => SchoolLevel::pluck('name', 'id')->toArray(),
            'schools' => School::orderBy('name', 'asc')->pluck('name', 'id')->toArray(),
        ];
        return view('admin.teacher.index', $view);
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
                    return '<a class="btn btn-sm btn-success" href="'.route('admin.teacher.show', $data->id).'" title="'.__("See detail").'"><i class="fa fa-eye"></i> '.__("See").'</a> <a class="btn btn-sm btn-warning" href="'.route('admin.teacher.edit', $data->id).'" title="'.__("Edit").'"><i class="fa fa-edit"></i> '.__("Edit").'</a>';
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
        if ( ! auth()->guard('admin')->user()->can('create ' . $this->table)) {
            return redirect()->route('admin.teacher.index')->with('alert-danger', __($this->noPermission));
        }
        $view = [
            'title' => __('Create Teacher'),
            'breadcrumbs' => [
                route('admin.teacher.index') => __('Teacher'),
                null => __('Create')
            ],
            'schools' => School::orderBy('name', 'asc')->pluck('name', 'id')->toArray(),
        ];
        return view('admin.teacher.create', $view);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTeacher $request)
    {
        if ( ! auth()->guard('admin')->user()->can('create ' . $this->table)) {
            return redirect()->route('admin.teacher.index')->with('alert-danger', __($this->noPermission));
        }
        $request->merge([
            'date_of_birth' => date('Y-m-d', strtotime($request->date_of_birth)),
        ]);
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
        if ( ! auth()->guard('admin')->user()->can('read ' . $this->table)) {
            return redirect()->route('admin.teacher.index')->with('alert-danger', __($this->noPermission));
        }
        $view = [
            'title' => __('Teacher Detail'),
            'breadcrumbs' => [
                route('admin.teacher.index') => __('Teacher'),
                null => __('Detail')
            ],
            'schools' => School::orderBy('name', 'asc')->pluck('name', 'id')->toArray(),
            'data' => $teacher
        ];
        return view('admin.teacher.show', $view);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Teacher  $teacher
     * @return \Illuminate\Http\Response
     */
    public function edit(Teacher $teacher)
    {
        if ( ! auth()->guard('admin')->user()->can('update ' . $this->table)) {
            return redirect()->route('admin.teacher.index')->with('alert-danger', __($this->noPermission));
        }
        $view = [
            'title' => __('Edit Teacher'),
            'breadcrumbs' => [
                route('admin.teacher.index') => __('Teacher'),
                null => __('Edit')
            ],
            'schools' => School::orderBy('name', 'asc')->pluck('name', 'id')->toArray(),
            'data' => $teacher
        ];
        return view('admin.teacher.edit', $view);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Teacher  $teacher
     * @return \Illuminate\Http\Response
     */
    public function update(StoreTeacher $request, Teacher $teacher)
    {
        if ( ! auth()->guard('admin')->user()->can('update ' . $this->table)) {
            return redirect()->route('admin.teacher.index')->with('alert-danger', __($this->noPermission));
        }
        $request->merge([
            'date_of_birth' => date('Y-m-d', strtotime($request->date_of_birth)),
        ]);
        $teacher->fill($request->except(['terms', 'submit']));
        $teacher->photo = $this->uploadPhoto($teacher, $request, $teacher->photo);
        $teacher->save();
        return redirect(url()->previous())->with('alert-success', __($this->updatedMessage));
    }

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
        Teacher::destroy($request->selectedData);
        return response()->json(['status' => true, 'message' => __($this->deletedMessage)]);
    }
}
