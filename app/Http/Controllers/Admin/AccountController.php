<?php

namespace App\Http\Controllers\Admin;

use Auth;
use App\Admin\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DataTables;
use Validator;
use Spatie\Image\Image;
use Spatie\Image\Manipulations;

class AccountController extends Controller
{
    private $createdMessage;
    private $updatedMessage;
    private $noPermission;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
        $this->createdMessage = 'Data successfully created.';
        $this->updatedMessage = 'Data successfully updated';
        $this->noPermission = 'You have no related permission.';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (auth()->guard('admin')->user()->hasRole('user')) {
            return $this->me();
        } if ( ! auth()->guard('admin')->user()->can('access accounts')) {
            return redirect()->route('admin.home')->with('alert-danger', $this->noPermission);
        }
        $view = [
            'title' => 'Account',
            'breadcrumbs' => [
                route('admin.account.index') => 'Account',
                null => 'Data'
            ]
        ];
        return view('admin.account.index', $view);
    }

    /**
     * Show a listing of the resource for datatable.
     * 
     * @param  \Illuminate\Http\Request  $request
     */
    public function list(Request $request)
    {
        if ($request->ajax()) {
            $accounts = User::list($request);
            return DataTables::of($accounts)
                ->addColumn('DT_RowIndex', function ($data)
                {
                    return '<div class="checkbox icheck"><label><input type="checkbox" name="selectedData[]" value="'.$data->id.'"></label></div>';
                })
                ->editColumn('created_at', function($data) {
                    return (date('d-m-Y h:m:s', strtotime($data->created_at)));
                })
                ->editColumn('avatar', function($data) {
                    $user = User::find($data->id);
                    return '<img src="'.asset($user->avatar).'" height="30" width="auto" alt="">';
                })
                ->addColumn('action', function($data) {
                    return '<a class="btn btn-sm btn-success" href="'.route('admin.account.show', $data->id).'" title="See detail"><i class="fa fa-eye"></i> See</a> <a class="btn btn-sm btn-warning" href="'.route('admin.account.edit', $data->id).'" title="Edit"><i class="fa fa-edit"></i> Edit</a>';
                })
                ->rawColumns(['DT_RowIndex', 'avatar', 'action'])
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
        if ( ! auth()->guard('admin')->user()->can('create accounts')) {
            return redirect()->route('admin.account.index')->with('alert-danger', $this->noPermission);
        }
        $view = [
            'title' => 'Create Account',
            'breadcrumbs' => [
                route('admin.account.index') => 'Account',
                null => 'Create'
            ]
        ];
        return view('admin.account.create', $view);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ( ! auth()->guard('admin')->user()->can('create accounts')) {
            return redirect()->route('admin.account.index')->with('alert-danger', $this->noPermission);
        }
        Validator::make($request->all(), User::rules())->validate();
        $request->merge(['password' => bcrypt($request->password)]);
        $user = User::create($request->all());
        $this->uploadPhoto($user, $request);
        $user->assignRole('user');
        return redirect(url()->previous())->with('alert-success', $this->createdMessage);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Admin\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, User $user)
    {
        // if ( ! auth()->guard('admin')->user()->can('read accounts'))
        // return redirect()->route('account.index')->with('alert-danger', $this->noPermission);
        $view = [
            'title' => 'Account Detail',
            'breadcrumbs' => [
                route('admin.account.index') => 'Account',
                null => 'Detail'
            ],
            'user' => $user
        ];
        if ($request->is('admin/account/me/*')) {
            $addonView = [
                'subtitle' => 'Hi, ' . $user->name . '!',
                'description' => 'Change information about yourself on this page.',
            ];
            $view = array_merge($view, $addonView);
        }
        return view('admin.account.show', $view);
    }

    /**
     * Display the current user's resource.
     * 
     * @return @show()
     */
    public function me(Request $request)
    {
        return $this->show($request, auth()->guard('admin')->user());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Admin\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        if ( ! auth()->guard('admin')->user()->can('update accounts')) {
            return redirect()->route('account.index')->with('alert-danger', $this->noPermission);
        }
        $view = [
            'title' => 'Edit Account',
            'breadcrumbs' => [
                route('admin.account.index') => 'Account',
                null => 'Edit'
            ],
            'user' => $user
        ];
        return view('admin.account.edit', $view);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Admin\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        // if ( ! auth()->guard('admin')->user()->can('update accounts'))
        // return redirect()->route('account.index')->with('alert-danger', $this->noPermission);
        Validator::make($request->all(), User::rules('update'))->validate();
        if ($request->filled('password')) {
            $request->merge(['password' => bcrypt($request->password)]);
        } else {
            $request->merge(['password' => $user->password]);
        }
        $user->fill($request->all());
        $this->uploadPhoto($user, $request);
        return redirect(url()->previous())->with('alert-success', $this->updatedMessage);
    }

    /**
     * Upload photo for user avatar
     * 
     * @param  \App\User  $user
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $oldFile
     * @return string
     */
    public function uploadPhoto($user, Request $request)
    {
        if ($request->hasFile('photo')) {
            $user->addMediaFromRequest('photo')->usingFileName('photo-'.date('d-m-Y-h-m-s-').md5(uniqid(rand(), true)))->toMediaCollection('photos');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Admin\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if ( ! auth()->guard('admin')->user()->can('delete accounts')) {
            return response()->json(['status' => false, 'message' => $this->noPermission], 422);
        }
        User::destroy($request->selectedData);
        return response()->json(['status' => true, 'message' => 'Data successfully deleted.']);
    }
}
