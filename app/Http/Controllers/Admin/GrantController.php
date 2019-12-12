<?php

namespace App\Http\Controllers\Admin;

use App\Grant;
use App\School;
use App\Pic;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreGrant;
use DataTables;
use App\Exports\GrantsExport;

class GrantController extends Controller
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
        $this->table = 'grants';
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
            'title' => __('Grant'),
            'breadcrumbs' => [
                route('admin.grant.index') => __('Grant'),
                null => 'Data'
            ],
            'schools' => School::pluck('name', 'id')->toArray(),
        ];
        return view('admin.grant.index', $view);
    }

    /**
     * Display a listing of the deleted resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function bin()
    {
        if ( ! auth()->guard('admin')->user()->can('bin ' . $this->table)) {
            return redirect()->route('admin.grant.index')->with('alert-danger', __($this->noPermission));
        }
        $view = [
            'back' => route('admin.grant.index'),
            'title' => __('Deleted Grant Application'),
            'breadcrumbs' => [
                route('admin.subsidy.index') => __('Grant Application'),
                null => __('Deleted')
            ],
        ];
        return view('admin.grant.bin', $view);
    }

    /**
     * Show a listing of the resource for datatable.
     * 
     * @param  \Illuminate\Http\Request  $request
     */
    public function list(Request $request)
    {
        if ($request->ajax()) {
            $grants = Grant::list($request);
            return DataTables::of($grants)
                ->addColumn('DT_RowIndex', function ($data) {
                    return '<div class="checkbox icheck"><label><input type="checkbox" name="selectedData[]" value="'.$data->id.'"></label></div>';
                })
                ->editColumn('created_at', function($data) {
                    return (date('d-m-Y H:i:s', strtotime($data->created_at)));
                })
                ->addColumn('action', function($data) {
                    return '<a class="btn btn-sm btn-success" href="'.route('admin.grant.show', $data->id).'" title="'.__("See detail").'"><i class="fa fa-eye"></i> '.__("See").'</a> <a class="btn btn-sm btn-warning" href="'.route('admin.grant.edit', $data->id).'" title="'.__("Edit").'"><i class="fa fa-edit"></i> '.__("Edit").'</a>';
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
            return redirect()->route('admin.grant.index')->with('alert-danger', __($this->noPermission));
        }
        $view = [
            'back' => route('admin.grant.index'),
            'title' => __('Create Grant Application'),
            'breadcrumbs' => [
                route('admin.grant.index') => __('Grant'),
                null => __('Create')
            ],
            'schools' => School::pluck('name', 'id')->toArray(),
        ];
        return view('admin.grant.create', $view);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreGrant $request)
    {
        if ( ! auth()->guard('admin')->user()->can('create ' . $this->table)) {
            return redirect()->route('admin.grant.index')->with('alert-danger', __($this->noPermission));
        }
        $grant = Grant::create($request->all());
        $this->savePic($grant, $request);
        return redirect(url()->previous())->with('alert-success', __($this->createdMessage));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Grant  $grant
     * @return \Illuminate\Http\Response
     */
    public function show(Grant $grant)
    {
        if ( ! auth()->guard('admin')->user()->can('read ' . $this->table)) {
            return redirect()->route('admin.grant.index')->with('alert-danger', __($this->noPermission));
        }
        $view = [
            'back' => route('admin.grant.index'),
            'title' => __('Grant Application Detail'),
            'breadcrumbs' => [
                route('admin.grant.index') => __('Grant'),
                null => __('Detail')
            ],
            'schools' => School::pluck('name', 'id')->toArray(),
            'data' => $grant
        ];
        return view('admin.grant.show', $view);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Grant  $grant
     * @return \Illuminate\Http\Response
     */
    public function edit(Grant $grant)
    {
        if ( ! auth()->guard('admin')->user()->can('update ' . $this->table)) {
            return redirect()->route('admin.grant.index')->with('alert-danger', __($this->noPermission));
        }
        $view = [
            'back' => route('admin.grant.index'),
            'title' => __('Edit Grant Application'),
            'breadcrumbs' => [
                route('admin.grant.index') => __('Grant'),
                null => __('Edit')
            ],
            'schools' => School::pluck('name', 'id')->toArray(),
            'data' => $grant
        ];
        return view('admin.grant.edit', $view);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Grant  $grant
     * @return \Illuminate\Http\Response
     */
    public function update(StoreGrant $request, Grant $grant)
    {
        if ( ! auth()->guard('admin')->user()->can('update ' . $this->table)) {
            return redirect()->route('admin.grant.index')->with('alert-danger', __($this->noPermission));
        }
        $grant->fill($request->all());
        $grant->save();
        $this->savePic($grant, $request);
        return redirect(url()->previous())->with('alert-success', __($this->updatedMessage));
    }

    /**
     * Save pic
     * 
     * @param  \App\Grant  $grant
     * @param  \Illuminate\Http\Request  $request
     */
    public function savePic($grant, Request $request)
    {
        $pic = Pic::bySchool($request->school_id)->first();
        if ($request->isMethod('put')) {
            $schoolPic = Pic::bySchool($grant->school_id)->where('id', $grant->grantPic->pic->id)->first();
            if ( ! $schoolPic) {
                Pic::destroy($grant->grantPic->pic->id);
            }
            $request->request->add(['pic' => 1]);
        }
        if ($request->pic == 1) {
            $pic = Pic::firstOrCreate([
                'name' => $request->pic_name,
                'position' => $request->pic_position,
                'phone_number' => $request->pic_phone_number,
                'email' => $request->pic_email
            ]);
        }
        $grant->pic()->sync([$pic->id]);
    }

    /**
     * Export subsidy listing as Excel
     * 
     * @param  \Illuminate\Http\Request  $request
     */
    public function export(Request $request)
    {
        return (new GrantsExport($request))->download('grant-'.date('d-m-Y-h-i-s').'.xlsx');
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
        Grant::destroy($request->selectedData);
        return response()->json(['status' => true, 'message' => __($this->deletedMessage)]);
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function restore(Request $request)
    {
        if ( ! auth()->guard('admin')->user()->can('restore ' . $this->table)) {
            return response()->json(['status' => false, 'message' => __($this->noPermission)], 422);
        }
        Grant::onlyTrashed()->whereIn('id', $request->selectedData)->restore();
        return response()->json(['status' => true, 'message' => __($this->restoredMessage)]);
    }

    /**
     * Remove permanently the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroyPermanently(Request $request)
    {
        if ( ! auth()->guard('admin')->user()->can('force_delete ' . $this->table)) {
            return response()->json(['status' => false, 'message' => __($this->noPermission)], 422);
        }
        Grant::onlyTrashed()->whereIn('id', $request->selectedData)->forceDelete();
        return response()->json(['status' => true, 'message' => __($this->deletedMessage)]);
    }
}
