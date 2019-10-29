<?php

namespace App\Http\Controllers\Admin;

use App\Admin\User as Staff;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;

class SettingController extends Controller
{
    private $table;
    private $settings;

    /**
     * Create a new class instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth:admin');
        $this->table = 'settings';
        $this->settings = [
            [
                'title' => __('General'),
                'description' => __('General settings such as, site title, site description, address and so on.'),
                'icon' => 'fas fa-cog',
                'url' => '#',
            ],
            [
                'title' => __('Role'),
                'description' => __('Account role settings, such as supersu, admin, and user.'),
                'icon' => 'fas fa-users-cog',
                'url' => route('admin.setting.role.index'),
            ],
            [
                'title' => __('Permission'),
                'description' => __('Role permission settings, such as manage data, CRUD, and approval.'),
                'icon' => 'fas fa-th-list',
                'url' => '#',
            ],
            [
                'title' => __('Form'),
                'description' => __('Form settings, such as open or close the form.'),
                'icon' => 'fas fa-stopwatch',
                'url' => '#',
            ],
            [
                'title' => __('Training'),
                'description' => __('Training settings, such as quota limit, and payment nominal.'),
                'icon' => 'fas fa-business-time',
                'url' => '#',
            ],
            [
                'title' => __('Exam Readiness'),
                'description' => __('Exam readiness settings, such as reference school.'),
                'icon' => 'fas fa-book-reader',
                'url' => '#',
            ],
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
            return redirect()->route('admin.home')->with('alert-danger', __($this->noPermission));
        }
        $view = [
            'title' => __('Setting'),
            'breadcrumbs' => [
                route('admin.setting.index') => __('Setting'),
                null => __('Edit')
            ],
            'subtitle' => __('Overview'),
            'description' => __('Organize and adjust all settings about this site.'),
            'settings' => $this->settings,
        ];
        return view('admin.setting.index', $view);
    }

    public function role()
    {
        if ( ! auth()->guard('admin')->user()->can('access role ' . $this->table)) {
            return redirect()->route('admin.setting.index')->with('alert-danger', __($this->noPermission));
        }
        $view = [
            'back' => route('admin.setting.index'),
            'title' => __('Role Settings'),
            'breadcrumbs' => [
                route('admin.setting.index') => __('Setting'),
                route('admin.setting.role.index') => __('Role'),
                null => __('Edit')
            ],
            'subtitle' => __('All About Role Settings'),
            'description' => __('You can adjust all role settings here'),
            'settings' => $this->settings,
            'roles' => Role::pluck('name', 'id')->toArray(),
        ];
        return view('admin.setting.role.index', $view);
    }

    public function roleStore(Request $request)
    {
        $role = Role::find($request->role);
        foreach ($request->potential_staffs as $id) {
            $staff = Staff::find($id);
            $staff->syncRoles([$role->name]);
        }
        return redirect(url()->previous())->with('alert-success', __($this->updatedMessage));
    }
}
