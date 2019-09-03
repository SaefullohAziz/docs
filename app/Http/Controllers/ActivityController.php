<?php

namespace App\Http\Controllers;

use Auth;
use App\Activity;
use App\School;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    private $table;
    private $types;

    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
        $this->table = 'subsidies';
        $this->types = [
                'MOU' => 'M.O.U',
                'Kunjungan_industri' => 'Kunjungan Industri',
                'SSP Pendampingan' => 'SSP Pendampingan',
                'AYR' => 'AYR',
                'Axioo_Mengajar' => 'Axioo Mengajar'
            ];
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $view = [
            'title' => __('Activity Submission'),
            'breadcrumbs' => [
                null => __('Activity')
            ],
            'types' => $this->types,
            'schools' => School::where('id', Auth::user()->school_id)->pluck('name', 'id')->toArray(),
        ];
        return view('activity_submission.index', $view);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $view = [
            'title' => __('Activity_Submission'),
            'breadcrumbs' => [
                route('activity.index') => __('Activity'),
                null => 'Data'
            ],
            'types' => $this->types,
            'schools' => School::where('id', Auth::user()->school_id)->pluck('name', 'id')->toArray(),
        ];
        return view('activity_submission.create', $view);
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
     * @param  \App\Activity  $activity
     * @return \Illuminate\Http\Response
     */
    public function show(Activity $activity)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Activity  $activity
     * @return \Illuminate\Http\Response
     */
    public function edit(Activity $activity)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Activity  $activity
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Activity $activity)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Activity  $activity
     * @return \Illuminate\Http\Response
     */
    public function destroy(Activity $activity)
    {
        //
    }
}
