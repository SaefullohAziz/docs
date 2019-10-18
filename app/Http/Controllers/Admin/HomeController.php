<?php

namespace App\Http\Controllers\Admin;

use Auth;
use App\Island;
use App\Province;
use App\SchoolLevel;
use App\SchoolStatus;
use App\Department;
use App\School;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $view = [
            'title' => __('Home'),
            'schoolComments' => SchoolLevel::with(['schoolComments' => function ($query) {
                $query->latest('created_at')->limit(20);
            }])->orderBy('created_at', 'asc')->get(),
            'statusMovements' => SchoolLevel::with(['statusUpdates' => function ($query) {
                $query->latest('created_at')->limit(10);
            }, 'statusUpdates.school', 'statusUpdates.status', 'statusUpdates.staff'])->get(),
            'schoolPerProvince' => Province::withCount('schools')->get()->toArray(),
            'islands' => Island::pluck('name', 'id')->toArray(),
            'provinces' => Province::pluck('name', 'id')->toArray(),
            'levels' => SchoolLevel::pluck('name', 'id')->toArray(),
            'statuses' => SchoolStatus::pluck('name', 'id')->toArray(),
            'studentPerDepartment' => Department::withCount('students')->get()->toArray(),
            'departments' => Department::pluck('name', 'id')->toArray(),
        ];
        return view('admin.home.index', $view);
    }
}
