<?php

namespace App\Http\Controllers;

use App\Department;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
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
            'studentPerDepartment' => Department::withCount(['students' => function ($query) {
                $query->whereHas('school', function ($subQuery) {
                    $subQuery->where('schools.id', auth()->user()->school->id);
                });
            }])->get()->toArray(),
            'departments' => Department::pluck('name', 'id')->toArray(),
        ];
        return view('home.index', $view);
    }
}
