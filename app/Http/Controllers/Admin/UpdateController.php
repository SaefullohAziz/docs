<?php

namespace App\Http\Controllers\Admin;

use App\SchoolLevel;
use App\School;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UpdateController extends Controller
{
    private $table;
    private $updates;

    /**
     * Create a new class instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->table = 'updates';
        $this->updates = collect([
            [
                'title' => __('Status'),
                'slug' => 'status',
                'description' => __('Update multiple school status data.'),
                'icon' => 'fas fa-level-up-alt',
                'url' => route('admin.update.status.index'),
            ],
        ]);
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
            'title' => __('Update Data'),
            'breadcrumbs' => [
                route('admin.update.index') => __('Update'),
                null => __('Edit')
            ],
            'subtitle' => __('Overview'),
            'description' => __('Update school related data.'),
            'updates' => $this->updates,
        ];
        return view('admin.update.index', $view);
    }

    /**
     * Show school status update page
     *
     * @return void
     */
    public function status()
    {
        if ( ! auth()->guard('admin')->user()->can('access status ' . $this->table)) {
            return redirect()->route('admin.update.index')->with('alert-danger', __($this->noPermission));
        }
        $view = [
            'back' => route('admin.update.index'),
            'title' => __('Status Update'),
            'breadcrumbs' => [
                route('admin.update.index') => __('Update'),
                route('admin.update.status.index') => __('Status'),
                null => __('Edit')
            ],
            'subtitle' => __('All About General Settings'),
            'description' => __('You can adjust all general settings here'),
            'navs' => $this->updates,
            'update' => $this->updates->where('slug', 'status')->first(),
            'levels' => SchoolLevel::pluck('name', 'id')->toArray(),
            'schools' => School::pluck('name', 'id')->toArray(),
        ];
        return view('admin.update.status.index', $view);
    }

    public function statusStore(Request $request)
    {
        for ($i=0; $i < count($request->school_id); $i++) { 
            $school = School::find($request->school_id[$i]);
            if ( ! empty($request->status_id[$i])) {
                $school->statusUpdates()->create([
                    'school_status_id' => $request->status_id[$i],
                    'created_by' => 'staff',
                    'staff_id' => auth()->guard('admin')->user()->id,
                ]);
            }
        }
        return redirect(url()->previous())->with('alert-success', __($this->updatedMessage));
    }
}
