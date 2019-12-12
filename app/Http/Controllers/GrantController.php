<?php

namespace App\Http\Controllers;

use App\Grant;
use App\Pic;
use Illuminate\Http\Request;
use App\Http\Requests\StoreGrant;
use Route;
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
        $view = [
            'title' => __('Grant'),
            'breadcrumbs' => [
                route('grant.index') => __('Grant'),
                null => 'Data'
            ],
        ];
        return view('grant.index', $view);
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
                    return '<a class="btn btn-sm btn-success" href="'.route('grant.show', $data->id).'" title="'.__("See detail").'"><i class="fa fa-eye"></i> '.__("See").'</a> ' . (Route::has('grant.edit')?'<a class="btn btn-sm btn-warning" href="'.route('grant.edit', $data->id).'" title="'.__("Edit").'"><i class="fa fa-edit"></i> '.__("Edit").'</a>':'');
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
            'back' => route('grant.index'),
            'title' => __('Create Grant Application'),
            'breadcrumbs' => [
                route('grant.index') => __('Grant'),
                null => __('Create')
            ],
        ];
        return view('grant.create', $view);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->request->add(['school_id' => $request->user()->school->id]);
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
        if (auth()->user()->cant('view', $grant)) {
            return redirect()->route('grant.index')->with('alert-danger', __($this->unauthorizedMessage));
        }
        $view = [
            'back' => route('grant.index'),
            'title' => __('Grant Application Detail'),
            'breadcrumbs' => [
                route('grant.index') => __('Grant'),
                null => __('Detail')
            ],
            'data' => $grant
        ];
        return view('grant.show', $view);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Grant  $grant
     * @return \Illuminate\Http\Response
     */
    public function edit(Grant $grant)
    {
        if (auth()->user()->cant('update', $grant)) {
            return redirect()->route('grant.index')->with('alert-danger', __($this->unauthorizedMessage));
        }
        $view = [
            'back' => route('grant.index'),
            'title' => __('Edit Grant Application'),
            'breadcrumbs' => [
                route('grant.index') => __('Grant'),
                null => __('Edit')
            ],
            'data' => $grant
        ];
        return view('grant.edit', $view);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Grant  $grant
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Grant $grant)
    {
        if (auth()->user()->cant('update', $grant)) {
            return redirect()->route('grant.index')->with('alert-danger', __($this->unauthorizedMessage));
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
     * Remove the specified resource from storage.
     *
     * @param  \App\Grant  $grant
     * @return \Illuminate\Http\Response
     */
    public function destroy(Grant $grant)
    {
        //
    }
}
