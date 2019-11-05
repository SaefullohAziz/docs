<?php

namespace App\Http\Controllers\Admin;

use Auth;
use Excel;
use App\StudentClass;
use App\Student;
use App\Province;
use App\School;
use App\SchoolLevel;
use Illuminate\Http\Request;
use App\Imports\StudentImport;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStudent;
use DataTables;
use Validator;
use App\Exports\StudentsExport;
use Spatie\Image\Image;
use Spatie\Image\Manipulations;

class StudentController extends Controller
{
    private $table;
    private $parentEducations;
    private $parentEarnings;
	private $economyStatuses;
	private $religions;
	private $bloodTypes;
	private $mileages;

    /**
     * Create a new class instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->table = 'students';
        $this->parentEducations = [
			'' => 'Pilih',
			'SD' => 'SD',
			'SMP / Sederajat' => 'SMP / Sederajat',
			'SMA / Sederajat' => 'SMA / Sederajat',
			'S1' => 'S1',
			'S2' => 'S2',
			'S3' => 'S3'
        ];
        $this->parentEarnings = [
			'' => 'Pilih',
			'0' => '0',
			'Kurang dari 1.000.000' => 'Kurang dari 1.000.000',
			'1.000.000 - 2.000.000' => '1.000.000 - 2.000.000',
			'Lebih dari 2.000.000' => 'Lebih dari 2.000.000'
		];
		$this->economyStatuses = [
			'' => 'Pilih',
			'Menengah Bawah' => 'Menengah Bawah',
			'Menengah' => 'Menengah',
			'Menengah Atas' => 'Menengah Atas'
		];
		$this->religions = [
			'' => 'Pilih',
			'Islam' => 'Islam',
			'Kristen' => 'Kristen',
			'Katholik' => 'Katholik',
			'Budha' => 'Budha',
			'Hindu' => 'Hindu',
			'Lain-Lain' => 'Lain-Lain'
		];
		$this->bloodTypes = [
			'' => 'Pilih',
			'A' => 'A',
			'AB' => 'AB',
			'B' => 'B',
			'O' => 'O'
		];
		$this->mileages = [
			'' => 'Pilih',
			'Kurang dari 1 Km' => 'Kurang dari 1 Km',
			'Lebih dari 1 Km' => 'Lebih dari 1 Km'
		];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(StudentClass $studentClass)
    {
        if ( ! auth()->guard('admin')->user()->can('access ' . $this->table)) {
            return redirect()->route('admin.home')->with('alert-danger', __($this->noPermission));
        }
        $view = [
            'title' => __('Student'),
            'subtitle' => $studentClass->generation . ' - ' .$studentClass->school_year. ', ' . $studentClass->department->name,
            'description' => $studentClass->school->name,
            'breadcrumbs' => [
                route('admin.class.index') => __('Class'),
                route('admin.class.student.index', $studentClass->id) => __('Student'),
                null => 'Data'
            ],
            'levels' => SchoolLevel::pluck('name', 'id')->toArray(),
            'schools' => School::pluck('name', 'id')->toArray(),
            'studentClass' => $studentClass
        ];
        return view('admin.class.student.index', $view);
    }

    /**
     * Show a listing of the resource for datatable.
     * 
     * @param  \Illuminate\Http\Request  $request
     */
    public function list(Request $request, StudentClass $studentClass)
    {
        if ($request->ajax()) {
            $request->request->add(['class' => $studentClass->id]);
            $students = Student::list($request);
            return DataTables::of($students)
                ->addColumn('DT_RowIndex', function ($data) {
                    return '<div class="checkbox icheck"><label><input type="checkbox" name="selectedData[]" value="'.$data->id.'"></label></div>';
                })
                ->editColumn('created_at', function($data) {
                    return (date('d-m-Y h:m:s', strtotime($data->created_at)));
                })
                ->addColumn('action', function($data) use ($studentClass) {
                    return '<a class="btn btn-sm btn-success" href="'.route('admin.class.student.show', ['studentClass' => $studentClass->id, 'student' => $data->id]).'" title="'.__("See detail").'"><i class="fa fa-eye"></i> '.__("See").'</a> <a class="btn btn-sm btn-warning" href="'.route('admin.class.student.edit', ['studentClass' => $studentClass->id, 'student' => $data->id]).'" title="'.__("Edit").'"><i class="fa fa-edit"></i> '.__("Edit").'</a>';
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
    public function create(StudentClass $studentClass)
    {
        if ( ! auth()->guard('admin')->user()->can('create ' . $this->table)) {
            return redirect()->route('admin.class.student.index', $studentClass->id)->with('alert-danger', __($this->noPermission));
        }
        if (auth()->guard('admin')->user()->cant('adminCreateStudent', $studentClass)) {
            return redirect()->route('admin.class.student.index', $studentClass->id)->with('alert-danger', __($this->unauthorizedMessage));
        }
        for ($i=1; $i <= 20; $i++) { 
			$childOrders[$i] = $i;
        }
        for ($i=0; $i <= 20; $i++) { 
			$siblingNumbers[$i] = $i;
		}
        $view = [
            'title' => __('Create Student'),
            'subtitle' => $studentClass->generation . ' - ' .$studentClass->school_year. ', ' . $studentClass->department->name,
            'description' => $studentClass->school->name,
            'breadcrumbs' => [
                route('admin.class.index') => __('Class'),
                route('admin.class.student.index', $studentClass->id) => __('Student'),
                null => __('Create')
            ],
            'provinces' => Province::pluck('name', 'name')->toArray(),
            'parentEducations' => $this->parentEducations,
            'parentEarnings' => $this->parentEarnings,
            'economyStatuses' => $this->economyStatuses,
            'religions' => $this->religions,
            'bloodTypes' => $this->bloodTypes,
            'mileages' => $this->mileages,
            'childOrders' => $childOrders,
            'siblingNumbers' => $siblingNumbers,
            'studentClass' => $studentClass,
        ];
        return view('admin.class.student.create', $view);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreStudent $request, StudentClass $studentClass)
    {
        if ( ! auth()->guard('admin')->user()->can('create ' . $this->table)) {
            return redirect()->route('admin.class.student.index', $studentClass->id)->with('alert-danger', __($this->noPermission));
        }
        if (auth()->guard('admin')->user()->cant('adminCreateStudent', $studentClass)) {
            return redirect()->route('admin.class.student.index', $studentClass->id)->with('alert-danger', __($this->unauthorizedMessage));
        }
        $request->merge([
            'dateofbirth' => date('Y-m-d', strtotime($request->dateofbirth)),
        ]);
        $student = $studentClass->students()->create($request->except(['terms', 'submit']));
        $student->photo = $this->uploadPhoto($student, $request);
        $student->save();
        return redirect(url()->previous())->with('alert-success', __($this->createdMessage));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function show(StudentClass $studentClass, Student $student)
    {
        if ( ! auth()->guard('admin')->user()->can('read ' . $this->table)) {
            return redirect()->route('admin.class.student.index', $studentClass->id)->with('alert-danger', __($this->noPermission));
        }
        for ($i=1; $i <= 20; $i++) { 
			$childOrders[$i] = $i;
        }
        for ($i=0; $i <= 20; $i++) { 
			$siblingNumbers[$i] = $i;
		}
        $view = [
            'title' => __('Student Detail'),
            'subtitle' => $studentClass->generation . ' - ' .$studentClass->school_year. ', ' . $studentClass->department->name,
            'description' => $studentClass->school->name,
            'breadcrumbs' => [
                route('admin.class.index') => __('Class'),
                route('admin.class.student.index', $studentClass->id) => __('Student'),
                null => __('Detail')
            ],
            'provinces' => Province::pluck('name', 'name')->toArray(),
            'parentEducations' => $this->parentEducations,
            'parentEarnings' => $this->parentEarnings,
            'economyStatuses' => $this->economyStatuses,
            'religions' => $this->religions,
            'bloodTypes' => $this->bloodTypes,
            'mileages' => $this->mileages,
            'childOrders' => $childOrders,
            'siblingNumbers' => $siblingNumbers,
            'studentClass' => $studentClass,
            'data' => $student
        ];
        return view('admin.class.student.show', $view);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function edit(StudentClass $studentClass, Student $student)
    {
        if ( ! auth()->guard('admin')->user()->can('update ' . $this->table)) {
            return redirect()->route('admin.class.student.index', $studentClass->id)->with('alert-danger', __($this->noPermission));
        }
        if (auth()->guard('admin')->user()->cant('adminUpdateStudent', $studentClass)) {
            return redirect()->route('admin.class.student.index', $studentClass->id)->with('alert-danger', __($this->unauthorizedMessage));
        }
        for ($i=1; $i <= 20; $i++) { 
			$childOrders[$i] = $i;
        }
        for ($i=0; $i <= 20; $i++) { 
			$siblingNumbers[$i] = $i;
		}
        $view = [
            'title' => __('Edit Student'),
            'subtitle' => $studentClass->generation . ' - ' .$studentClass->school_year. ', ' . $studentClass->department->name,
            'description' => $studentClass->school->name,
            'breadcrumbs' => [
                route('admin.class.index') => __('Class'),
                route('admin.class.student.index', $studentClass->id) => __('Student'),
                null => __('Edit')
            ],
            'provinces' => Province::pluck('name', 'name')->toArray(),
            'parentEducations' => $this->parentEducations,
            'parentEarnings' => $this->parentEarnings,
            'economyStatuses' => $this->economyStatuses,
            'religions' => $this->religions,
            'bloodTypes' => $this->bloodTypes,
            'mileages' => $this->mileages,
            'childOrders' => $childOrders,
            'siblingNumbers' => $siblingNumbers,
            'studentClass' => $studentClass,
            'data' => $student,
        ];
        return view('admin.class.student.edit', $view);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function update(StoreStudent $request, StudentClass $studentClass, Student $student)
    {
        if ( ! auth()->guard('admin')->user()->can('update ' . $this->table)) {
            return redirect()->route('admin.class.student.index', $studentClass->id)->with('alert-danger', __($this->noPermission));
        }
        if (auth()->guard('admin')->user()->cant('adminUpdateStudent', $studentClass)) {
            return redirect()->route('admin.class.student.index', $studentClass->id)->with('alert-danger', __($this->unauthorizedMessage));
        }
        $request->merge([
            'dateofbirth' => date('Y-m-d', strtotime($request->dateofbirth)),
        ]);
        $student = $student->fill($request->except(['terms', 'submit']));
        $student->photo = $this->uploadPhoto($student, $request, $student->photo);
        $student->save();
        return redirect(url()->previous())->with('alert-success', __($this->updatedMessage));
    }

    /**
     * Upload photo
     * 
     * @param  \App\Student  $student
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $oldFile
     * @return string
     */
    public function uploadPhoto($student, Request $request, $oldFile = 'default.png')
    {
        if ($request->hasFile('photo')) {
            Image::load($request->photo)
                ->fit(Manipulations::FIT_CROP, 150, 150)
                ->optimize()
                ->save();
            $filename = 'photo_'.date('d_m_y_h_m_s_').md5(uniqid(rand(), true)).'.'.$request->photo->extension();
            $path = $request->photo->storeAs('public/student/photo/'.$student->id, $filename);
            return $student->id.'/'.$filename;
        }
        return $oldFile;
    }

    /**
     * Export student listing as Excel
     * 
     * @param  \Illuminate\Http\Request  $request
     */
    public function export(Request $request, StudentClass $studentClass)
    {
        $request->request->add(['class' => $studentClass->id]);
        return (new StudentsExport($request))->download('student-'.date('d-m-Y-h-m-s').'.xlsx');
    }

    /**
     * Import student from Excel
     * 
     * @param  \Illuminate\Http\Request  $request
     */
    public function importExcel(StudentClass $studentClass, Request $request)
    {
        if ( ! auth()->guard('admin')->user()->can('create ' . $this->table)) {
            return redirect()->route('admin.class.student.index', $studentClass->id)->with('alert-danger', __($this->noPermission));
        }
        if (auth()->guard('admin')->user()->cant('adminCreateStudent', $studentClass)) {
            return redirect()->route('admin.class.student.index', $studentClass->id)->with('alert-danger', __($this->unauthorizedMessage));
        }
        if ($studentClass->closed_at != null ) {
            return redirect()->route('admin.class.student.index', $studentClass->id)->with('alert-danger', __($this->unauthorizedMessage));
        }
        
        // validasi file input
        $this->validate($request, [
            'import_file' => 'required|mimes:xls,xlsx'
        ]);
        
        try {
            $data = Excel::import(new StudentImport($studentClass), $request->file('import_file'));
            return back()->with('alert-success', 'datas has been imported!');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $message = [];
            $row = 0;
            $attribute = [];
            foreach ($failures as $failure) {
                if ($row == $failure->row()) {
                        $attribute[] = $failure->attribute();
                }
                else{
                    $row++;
                    if ($attribute) {
                        $message[] = 'Error detected on row '. $failure->row() . ' on attribute ' . ucwords(implode(" , ", $attribute)) .' !';
                    }
                } 

                // get last foreach
                if( !next( $failures ) ) { 
                    $message[] = 'Error detected on row '. $failure->row() . ' on attribute ' . ucwords(implode(" , ", $attribute)) .' !';
                }

            }
            
            session()->flash( 'import_file', [
               'message' => $message
              ]);
            return back();
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, StudentClass $studentClass)
    {
        if ( ! auth()->guard('admin')->user()->can('delete ' . $this->table)) {
            return response()->json(['status' => false, 'message' => __($this->noPermission)], 422);
        }
        Student::destroy($request->selectedData);
        return response()->json(['status' => true, 'message' => __($this->deletedMessage)]);
    }
}
