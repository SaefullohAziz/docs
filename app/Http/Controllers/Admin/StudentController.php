<?php

namespace App\Http\Controllers\Admin;

use Auth;
use App\Student;
use App\Province;
use App\School;
use App\SchoolLevel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStudent;
use DataTables;
use Validator;
use App\Exports\StudentsExport;
use Spatie\Image\Image;
use Spatie\Image\Manipulations;

class StudentController extends Controller
{
    private $createdMessage;
    private $updatedMessage;
    private $deletedMessage;
    private $noPermission;
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
        $this->middleware('auth:admin');
        $this->createdMessage = __('Data successfully created.');
        $this->updatedMessage = __('Data successfully updated.');
        $this->deletedMessage = __('Data successfully deleted.');
        $this->noPermission = __('You have no related permission.');
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
    public function index()
    {
        if ( ! auth()->guard('admin')->user()->can('access ' . $this->table)) {
            return redirect()->route('admin.home')->with('alert-danger', $this->noPermission);
        }
        $view = [
            'title' => __('Student'),
            'breadcrumbs' => [
                route('admin.student.index') => __('Student'),
                null => 'Data'
            ],
            'levels' => SchoolLevel::pluck('name', 'id')->toArray(),
            'schools' => School::pluck('name', 'id')->toArray(),
        ];
        return view('admin.student.index', $view);
    }

    /**
     * Show a listing of the resource for datatable.
     * 
     * @param  \Illuminate\Http\Request  $request
     */
    public function list(Request $request)
    {
        if ($request->ajax()) {
            $students = Student::list($request);
            return DataTables::of($students)
                ->addColumn('DT_RowIndex', function ($data)
                {
                    return '<div class="checkbox icheck"><label><input type="checkbox" name="selectedData[]" value="'.$data->id.'"></label></div>';
                })
                ->editColumn('created_at', function($data) {
                    return (date('d-m-Y h:m:s', strtotime($data->created_at)));
                })
                ->addColumn('action', function($data) {
                    return '<a class="btn btn-sm btn-success" href="'.route('admin.student.show', $data->id).'" title="'.__("See detail").'"><i class="fa fa-eye"></i> '.__("See").'</a> <a class="btn btn-sm btn-warning" href="'.route('admin.student.edit', $data->id).'" title="'.__("Edit").'"><i class="fa fa-edit"></i> '.__("Edit").'</a>';
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
            return redirect()->route('admin.student.index')->with('alert-danger', $this->noPermission);
        }
        for ($i=3; $i >= -1; $i--) { 
            $schoolYears[date('Y', strtotime('-'.($i+1).' years')).'/'.date('Y', strtotime('-'.$i.' years'))] = date('Y', strtotime('-'.($i+1).' years')).'/'.date('Y', strtotime('-'.$i.' years'));
        }
        for ($i=10; $i <= 12; $i++) { 
			$grades['Kelas ' . $i] = 'Kelas ' . $i;
        }
        for ($i=1; $i <= 5; $i++) { 
			$generations['Angkatan ' . $i] = 'Angkatan ' . $i;
        }
        for ($i=1; $i <= 20; $i++) { 
			$childOrders[$i] = $i;
        }
        for ($i=0; $i <= 20; $i++) { 
			$siblingNumbers[$i] = $i;
		}
        $view = [
            'title' => __('Create Student'),
            'breadcrumbs' => [
                route('admin.student.index') => __('Student'),
                null => __('Create')
            ],
            'schools' => School::pluck('name', 'id')->toArray(),
            'provinces' => Province::pluck('name', 'name')->toArray(),
            'schoolYears' => $schoolYears,
            // 'departments' => Student::department()->pluck('department', 'department')->toArray(),
            'departments' => [
                'TKJ' => 'TKJ',
                'RPL' => 'RPL',
                'Multimedia' => 'Multimedia',
                'Telin' => 'Telin',
                'TI' => 'TI',
                'Teknik Jaringan Akses Telekomunikasi' => 'Teknik Jaringan Akses Telekomunikasi',
                'Lain-Lain' => 'Lain-Lain'
            ],
            'grades' => $grades,
            'generations' => $generations,
            'parentEducations' => $this->parentEducations,
            'parentEarnings' => $this->parentEarnings,
            'economyStatuses' => $this->economyStatuses,
            'religions' => $this->religions,
            'bloodTypes' => $this->bloodTypes,
            'mileages' => $this->mileages,
            'childOrders' => $childOrders,
            'siblingNumbers' => $siblingNumbers,
        ];
        return view('admin.student.create', $view);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreStudent $request)
    {
        if ( ! auth()->guard('admin')->user()->can('create ' . $this->table)) {
            return redirect()->route('admin.student.index')->with('alert-danger', $this->noPermission);
        }
        $request->merge([
            'dateofbirth' => date('Y-m-d', strtotime($request->dateofbirth)),
        ]);
        $student = Student::create($request->except(['terms', 'submit']));
        $student->photo = $this->uploadPhoto($student, $request);
        $student->save();
        return redirect(url()->previous())->with('alert-success', $this->createdMessage);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function show(Student $student)
    {
        if ( ! auth()->guard('admin')->user()->can('update ' . $this->table)) {
            return redirect()->route('admin.student.index')->with('alert-danger', $this->noPermission);
        }
        for ($i=3; $i >= -1; $i--) { 
            $schoolYears[date('Y', strtotime('-'.($i+1).' years')).'/'.date('Y', strtotime('-'.$i.' years'))] = date('Y', strtotime('-'.($i+1).' years')).'/'.date('Y', strtotime('-'.$i.' years'));
        }
        for ($i=10; $i <= 12; $i++) { 
			$grades['Kelas ' . $i] = 'Kelas ' . $i;
        }
        for ($i=1; $i <= 5; $i++) { 
			$generations['Angkatan ' . $i] = 'Angkatan ' . $i;
        }
        for ($i=1; $i <= 20; $i++) { 
			$childOrders[$i] = $i;
        }
        for ($i=0; $i <= 20; $i++) { 
			$siblingNumbers[$i] = $i;
		}
        $view = [
            'title' => __('Student Detail'),
            'breadcrumbs' => [
                route('admin.student.index') => __('Student'),
                null => __('Detail')
            ],
            'schools' => School::pluck('name', 'id')->toArray(),
            'provinces' => Province::pluck('name', 'name')->toArray(),
            'schoolYears' => $schoolYears,
            // 'departments' => Student::department()->pluck('department', 'department')->toArray(),
            'departments' => [
                'TKJ' => 'TKJ',
                'RPL' => 'RPL',
                'Multimedia' => 'Multimedia',
                'Telin' => 'Telin',
                'TI' => 'TI',
                'Teknik Jaringan Akses Telekomunikasi' => 'Teknik Jaringan Akses Telekomunikasi',
                'Lain-Lain' => 'Lain-Lain'
            ],
            'grades' => $grades,
            'generations' => $generations,
            'parentEducations' => $this->parentEducations,
            'parentEarnings' => $this->parentEarnings,
            'economyStatuses' => $this->economyStatuses,
            'religions' => $this->religions,
            'bloodTypes' => $this->bloodTypes,
            'mileages' => $this->mileages,
            'childOrders' => $childOrders,
            'siblingNumbers' => $siblingNumbers,
            'student' => $student
        ];
        return view('admin.student.show', $view);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function edit(Student $student)
    {
        if ( ! auth()->guard('admin')->user()->can('update ' . $this->table)) {
            return redirect()->route('admin.student.index')->with('alert-danger', $this->noPermission);
        }
        for ($i=3; $i >= -1; $i--) { 
            $schoolYears[date('Y', strtotime('-'.($i+1).' years')).'/'.date('Y', strtotime('-'.$i.' years'))] = date('Y', strtotime('-'.($i+1).' years')).'/'.date('Y', strtotime('-'.$i.' years'));
        }
        for ($i=10; $i <= 12; $i++) { 
			$grades['Kelas ' . $i] = 'Kelas ' . $i;
        }
        for ($i=1; $i <= 5; $i++) { 
			$generations['Angkatan ' . $i] = 'Angkatan ' . $i;
        }
        for ($i=1; $i <= 20; $i++) { 
			$childOrders[$i] = $i;
        }
        for ($i=0; $i <= 20; $i++) { 
			$siblingNumbers[$i] = $i;
		}
        $view = [
            'title' => __('Edit Student'),
            'breadcrumbs' => [
                route('admin.student.index') => __('Student'),
                null => __('Edit')
            ],
            'schools' => School::pluck('name', 'id')->toArray(),
            'provinces' => Province::pluck('name', 'name')->toArray(),
            'schoolYears' => $schoolYears,
            // 'departments' => Student::department()->pluck('department', 'department')->toArray(),
            'departments' => [
                'TKJ' => 'TKJ',
                'RPL' => 'RPL',
                'Multimedia' => 'Multimedia',
                'Telin' => 'Telin',
                'TI' => 'TI',
                'Teknik Jaringan Akses Telekomunikasi' => 'Teknik Jaringan Akses Telekomunikasi',
                'Lain-Lain' => 'Lain-Lain'
            ],
            'grades' => $grades,
            'generations' => $generations,
            'parentEducations' => $this->parentEducations,
            'parentEarnings' => $this->parentEarnings,
            'economyStatuses' => $this->economyStatuses,
            'religions' => $this->religions,
            'bloodTypes' => $this->bloodTypes,
            'mileages' => $this->mileages,
            'childOrders' => $childOrders,
            'siblingNumbers' => $siblingNumbers,
            'student' => $student
        ];
        return view('admin.student.edit', $view);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function update(StoreStudent $request, Student $student)
    {
        if ( ! auth()->guard('admin')->user()->can('update ' . $this->table)) {
            return redirect()->route('admin.student.index')->with('alert-danger', $this->noPermission);
        }
        $request->merge([
            'dateofbirth' => date('Y-m-d', strtotime($request->dateofbirth)),
        ]);
        $student = $student->fill($request->except(['terms', 'submit']));
        $student->photo = $this->uploadPhoto($student, $request, $student->photo);
        $student->save();
        return redirect(url()->previous())->with('alert-success', $this->updatedMessage);
    }

    /**
     * Upload photo for student
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
    public function export(Request $request)
    {
        return (new StudentsExport($request))->download('student-'.date('d-m-Y-h-m-s').'.xlsx');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if ( ! auth()->guard('admin')->user()->can('delete ' . $this->school)) {
            return response()->json(['status' => false, 'message' => $this->noPermission], 422);
        }
        Student::destroy($request->selectedData);
        return response()->json(['status' => true, 'message' => 'Data successfully deleted.']);
    }
}
