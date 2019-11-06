<?php

namespace App\Http\Controllers\Admin;

use App\Admin\User as Staff;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Image\Image;
use Spatie\Image\Manipulations;
use App\Department;

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
        $this->table = 'settings';
        $this->settings = [
            [
                'title' => __('General'),
                'description' => __('General settings such as, site title, site description, address and so on.'),
                'icon' => 'fas fa-cog',
                'url' => route('admin.setting.general.index'),
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
                'url' => route('admin.setting.form.index'),
            ],
            [
                'title' => __('Training'),
                'description' => __('Training settings, such as quota limit, and payment nominal.'),
                'icon' => 'fas fa-business-time',
                'url' => route('admin.setting.training.index'),
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

    /**
     * Show general settings page
     */
    public function general()
    {
        if ( ! auth()->guard('admin')->user()->can('access general ' . $this->table)) {
            return redirect()->route('admin.setting.index')->with('alert-danger', __($this->noPermission));
        }
        $view = [
            'back' => route('admin.setting.index'),
            'title' => __('General Settings'),
            'breadcrumbs' => [
                route('admin.setting.index') => __('Setting'),
                route('admin.setting.general.index') => __('General'),
                null => __('Edit')
            ],
            'subtitle' => __('All About General Settings'),
            'description' => __('You can adjust all general settings here'),
            'settings' => $this->settings,
        ];
        return view('admin.setting.general.index', $view);
    }

    /**
     * Save general settings into database
     */
    public function generalStore(Request $request)
    {
        if ( ! auth()->guard('admin')->user()->can('access general ' . $this->table)) {
            return redirect()->route('admin.setting.index')->with('alert-danger', __($this->noPermission));
        }
        setting([
            'site_logo' => $this->uploadLogo($request, setting('site_logo')),
        ])->save();
        return redirect(url()->previous())->with('alert-success', __($this->updatedMessage));
    }

    /**
     * Upload logo
     * 
     * @param  \App\Student  $student
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $oldFile
     * @return string
     */
    public function uploadLogo(Request $request, $oldFile = null)
    {
        if ($request->hasFile('site_logo')) {
            Image::load($request->site_logo)
                ->fit(Manipulations::FIT_CROP, 500, 115)
                ->optimize()
                ->save();
            $filename = 'logo_'.date('d_m_y_h_m_s_').md5(uniqid(rand(), true)).'.'.$request->site_logo->extension();
            $path = $request->site_logo->storeAs('public/file/', $filename);
            return 'file/' . $filename;
        }
        return $oldFile;
    }

    /**
     * Show role settings page
     */
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

    /**
     * Save role settings into database
     */
    public function roleStore(Request $request)
    {
        if ( ! auth()->guard('admin')->user()->can('access role ' . $this->table)) {
            return redirect()->route('admin.setting.index')->with('alert-danger', __($this->noPermission));
        }
        $role = Role::find($request->role);
        foreach ($request->potential_staffs as $id) {
            $staff = Staff::find($id);
            $staff->syncRoles([$role->name]);
        }
        return redirect(url()->previous())->with('alert-success', __($this->updatedMessage));
    }

    /**
     * Show form settings page
     */
    public function form()
    {
        if ( ! auth()->guard('admin')->user()->can('access form ' . $this->table)) {
            return redirect()->route('admin.setting.index')->with('alert-danger', __($this->noPermission));
        }
        $view = [
            'back' => route('admin.setting.index'),
            'title' => __('Form Settings'),
            'breadcrumbs' => [
                route('admin.setting.index') => __('Setting'),
                route('admin.setting.role.index') => __('Form'),
                null => __('Edit')
            ],
            'subtitle' => __('All About Form Settings'),
            'description' => __('You can adjust all form settings here'),
            'settings' => $this->settings,
            'forms' => json_decode(setting('form_settings')),
            'formLimiters' => [
                'None' => __('None'),
                'Quota' => __('Quota'),
                'Datetime' => __('Datetime'),
                'Both' => __('Both'),
            ]
        ];
        return view('admin.setting.form.index', $view);
    }

    /**
     * Save form settings into database
     */
    public function formStore(Request $request)
    {
        if ( ! auth()->guard('admin')->user()->can('access form ' . $this->table)) {
            return redirect()->route('admin.setting.index')->with('alert-danger', __($this->noPermission));
        }
        foreach (json_decode(setting('form_settings')) as $formSetting) {
            if (setting($formSetting->quota_limit_slug) != $request->{$formSetting->quota_limit_slug}) {
                $request->request->add([$formSetting->setting_created_at_slug => now()->toDateTimeString()]);
            }
            if ($request->filled($formSetting->time_limit_slug)) {
                $request->merge([$formSetting->time_limit_slug => date('Y-m-d h:m:s', strtotime($request->{$formSetting->time_limit_slug}))]);
            }
        }
        setting($request->except(['_token']))->save();
        return redirect(url()->previous())->with('alert-success', __($this->updatedMessage));
    }

    /**
     * Show Training settings page
     */
    public function training()
    {
        // dd(json_decode(setting('training_settings')));
        // if ( ! auth()->guard('admin')->user()->can('access training ' . $this->table)) {
        //     return redirect()->route('admin.setting.index')->with('alert-danger', __($this->noPermission));
        // }
        $view = [
            'back' => route('admin.setting.index'),
            'title' => __('Training Settings'),
            'breadcrumbs' => [
                route('admin.setting.index') => __('Setting'),
                route('admin.setting.training.index') => __('Training'),
                null => __('Edit')
            ],
            'subtitle' => __('All About Training Settings'),
            'description' => __('You can adjust all training settings here'),
            'settings' => $this->settings,
            'forms' => json_decode(setting('training_settings')),
            'formLimiters' => [
                'None' => __('None'),
                'Quota' => __('Quota'),
                'Datetime' => __('Datetime'),
                'Both' => __('Both'),
            ],
            'schoolLevels' => [
                'None' => __('None'),
                'Binaan' => __('Binaan'),
                'Rintisan' => __('Rintisan'),
                'Both' => __('Both'),
            ],
            'schoolImplementations' => Department::pluck('abbreviation', 'abbreviation')->toArray(),
        ];
        return view('admin.setting.training.index', $view);
    }

    /**
     * Save form settings into database
     */
    public function trainingStore(Request $request)
    {
        // if ( ! auth()->guard('admin')->user()->can('access form ' . $this->table)) {
        //     return redirect()->route('admin.setting.index')->with('alert-danger', __($this->noPermission));
        // }
        foreach (json_decode(setting('training_settings')) as $trainingSetting) {
            if (setting($trainingSetting->quota_limit_slug) != $request->{$trainingSetting->quota_limit_slug}) {
                $request->request->add([$trainingSetting->setting_created_at_slug => now()->toDateTimeString()]);
            }
            if ($request->filled($trainingSetting->time_limit_slug)) {
                $request->merge([$trainingSetting->time_limit_slug => date('Y-m-d h:m:s', strtotime($request->{$trainingSetting->time_limit_slug}))]);
            }
        }
        setting($request->except(['_token']))->save();
        return redirect(url()->previous())->with('alert-success', __($this->updatedMessage));
    }
}
