<?php

namespace App\Http\Controllers\Admin;

use App\Admin\User as Staff;
use App\User;
use App\School;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUser;
use DataTables;
use Spatie\Image\Image;
use Spatie\Image\Manipulations;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    private $table;
    private $types;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->table = 'accounts';
        $this->types = ['Staff' => 'Staff', 'School' => 'School'];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (auth()->guard('admin')->user()->hasRole('user')) {
            return redirect()->route('admin.account.me');
        } if ( ! auth()->guard('admin')->user()->can('access ' . $this->table)) {
            return redirect()->route('admin.home')->with('alert-danger', __($this->noPermission));
        }
        $view = [
            'title' => __('Account'),
            'breadcrumbs' => [
                route('admin.account.index') => __('Account'),
                null => __('Data')
            ],
            'types' => $this->types
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
            $accounts = Staff::list($request);
            return DataTables::of($accounts)
                ->addColumn('DT_RowIndex', function ($data) {
                    return '<div class="checkbox icheck"><label><input type="checkbox" name="selectedData[]" value="'.$data->id.'/'.$data->type.'"></label></div>';
                })
                ->editColumn('created_at', function($data) {
                    return (date('d-m-Y H:i:s', strtotime($data->created_at)));
                })
                ->editColumn('avatar', function($data) {
                    $user = Staff::find($data->id);
                    if ($data->type == 'School') {
                        $user = User::find($data->id);
                    }
                    return '<img src="'.asset($user->avatar).'" height="30" width="auto" alt="">';
                })
                ->addColumn('action', function($data) {
                    if ($data->type == 'School') {
                        return '<a class="btn btn-sm btn-success" href="'.route('admin.account.school.show', $data->id).'" title="'.__('See detail').'"><i class="fa fa-eye"></i> '.__('See').'</a> <a class="btn btn-sm btn-warning" href="'.route('admin.account.school.edit', $data->id).'" title="'.__('Edit').'"><i class="fa fa-edit"></i> '.__('Edit').'</a>';
                    }
                    return '<a class="btn btn-sm btn-success" href="'.route('admin.account.show', $data->id).'" title="'.__('See detail').'"><i class="fa fa-eye"></i> '.__('See').'</a> <a class="btn btn-sm btn-warning" href="'.route('admin.account.edit', $data->id).'" title="'.__('Edit').'"><i class="fa fa-edit"></i> '.__('Edit').'</a>';
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
        if ( ! auth()->guard('admin')->user()->can('create ' . $this->table)) {
            return redirect()->route('admin.account.index')->with('alert-danger', __($this->noPermission));
        }
        $view = [
            'title' => __('Create Account'),
            'breadcrumbs' => [
                route('admin.account.index') => __('Account'),
                null => __('Create')
            ],
            'types' => $this->types,
            'schools' => School::doesntHave('user')->pluck('name', 'id')->toArray()
        ];
        return view('admin.account.create', $view);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUser $request)
    {
        if ( ! auth()->guard('admin')->user()->can('create ' . $this->table)) {
            return redirect()->route('admin.account.index')->with('alert-danger', __($this->noPermission));
        }
        $request->merge(['password' => Hash::make($request->password)]);
        if ($request->type == 'Staff') {
            $user = Staff::create($request->all());
            $user->assignRole('user');
        } elseif ($request->type == 'School') {
            $request->merge(['username' => null]);
            $user = User::create($request->all());
        }
        $this->uploadPhoto($user, $request);
        return redirect(url()->previous())->with('alert-success', __($this->createdMessage));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Admin\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Staff $user)
    {
        if ( ! $request->is('admin/account/me')) {
            if ( ! auth()->guard('admin')->user()->can('read ' . $this->table)) {
                return redirect()->route('account.index')->with('alert-danger', __($this->noPermission));
            }
        }
        $view = [
            'title' => __('Account Detail'),
            'breadcrumbs' => [
                route('admin.account.index') => __('Account'),
                null => __('Detail')
            ],
            'data' => $user
        ];
        if ($request->is('admin/account/me/*')) {
            $addonView = [
                'subtitle' => __('Hi') . ', ' . $user->name . '!',
                'description' => __('Change information about yourself on this page.'),
            ];
            $view = array_merge($view, $addonView);
        }
        return view('admin.account.show', $view);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Admin\User  $user
     * @return \Illuminate\Http\Response
     */
    public function showSchool(Request $request, User $user)
    {
        if ( ! auth()->guard('admin')->user()->can('read ' . $this->table)) {
            return redirect()->route('account.index')->with('alert-danger', __($this->noPermission));
        }
        $view = [
            'title' => __('Account Detail'),
            'breadcrumbs' => [
                route('admin.account.index') => __('Account'),
                null => __('Detail')
            ],
            'data' => $user
        ];
        return view('admin.account.school.show', $view);
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
    public function edit(Staff $user)
    {
        if ( ! auth()->guard('admin')->user()->can('update ' . $this->table)) {
            return redirect()->route('account.index')->with('alert-danger', __($this->noPermission));
        }
        $view = [
            'title' => __('Edit Account'),
            'breadcrumbs' => [
                route('admin.account.index') => __('Account'),
                null => __('Edit')
            ],
            'data' => $user
        ];
        return view('admin.account.edit', $view);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Admin\User  $user
     * @return \Illuminate\Http\Response
     */
    public function editSchool(User $user)
    {
        if ( ! auth()->guard('admin')->user()->can('update ' . $this->table)) {
            return redirect()->route('account.index')->with('alert-danger', __($this->noPermission));
        }
        $view = [
            'title' => __('Edit Account'),
            'breadcrumbs' => [
                route('admin.account.index') => __('Account'),
                null => __('Edit')
            ],
            'data' => $user
        ];
        return view('admin.account.school.edit', $view);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Admin\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(StoreUser $request, Staff $user)
    {
        if ( ! $request->is('admin/account/me')) {
            if ( ! auth()->guard('admin')->user()->can('update ' . $this->table)) {
                return redirect()->route('account.index')->with('alert-danger', __($this->noPermission));
            }
        }
        $request->merge(['password' => $user->password]);
        if ($request->filled('password')) {
            $request->merge(['password' => Hash::make($request->password)]);
        }
        $user->fill($request->all());
        $user->save();
        $this->uploadPhoto($user, $request);
        return redirect(url()->previous())->with('alert-success', __($this->updatedMessage));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function updateSchool(StoreUser $request, User $user)
    {
        $request->merge(['password' => $user->password]);
        if ($request->filled('password')) {
            $request->merge(['password' => Hash::make($request->password)]);
        }
        $user->fill($request->except(['username', 'name']));
        $user->save();
        $this->uploadPhoto($user, $request);
        return redirect(url()->previous())->with('alert-success', __($this->updatedMessage));
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
    public function reset(Request $request)
    {
        $staffs = [];
        $schools = [];
        foreach ($request->selectedData as $data) {
            $explode = explode('/', $data);
            if(strtolower(end($explode)) == 'school'){
                $schools[] = $explode[0];
            } else {
                $staffs[] = $explode[0];
            }
        }
        Staff::whereIn('id', $staffs)->update(['password' => Hash::make('rememberthat')]);
        User::whereIn('id', $schools)->update(['password' => Hash::make('!Indo!Joss!')]);
        return response()->json(['status' => true, 'message' => __($this->updatedMessage)]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Admin\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if ( ! auth()->guard('admin')->user()->can('delete ' . $this->table)) {
            return response()->json(['status' => false, 'message' => __($this->noPermission)], 422);
        }
        $staffs = [];
        $schools = [];
        foreach ($request->selectedData as $data) {
            $explode = explode('/', $data);
            if(strtolower(end($explode)) == 'school'){
                $schools[] = $explode[0];
            } else {
                $staffs[] = $explode[0];
            }
        }
        Staff::destroy($staffs);
        User::destroy($schools);
        return response()->json(['status' => true, 'message' => __($this->deletedMessage)]);
    }
}
