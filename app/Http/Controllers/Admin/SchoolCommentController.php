<?php

namespace App\Http\Controllers\Admin;

use Auth;
use App\School;
use App\SchoolComment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SchoolCommentController extends Controller
{
    private $table;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->table = 'school_comments';
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
    public function store(Request $request, School $school)
    {
        $request->merge([
            'staff_id' => Auth::guard('admin')->user()->id,
            'message' => htmlentities($request->message),
        ]);
        $school->comments()->create($request->all());
        return redirect(url()->previous())->with('alert-success', __($this->createdMessage));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\SchoolComment  $schoolComment
     * @return \Illuminate\Http\Response
     */
    public function show(SchoolComment $schoolComment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\SchoolComment  $schoolComment
     * @return \Illuminate\Http\Response
     */
    public function edit(SchoolComment $schoolComment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\SchoolComment  $schoolComment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SchoolComment $schoolComment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\SchoolComment  $schoolComment
     * @return \Illuminate\Http\Response
     */
    public function destroy(SchoolComment $schoolComment)
    {
        //
    }
}
