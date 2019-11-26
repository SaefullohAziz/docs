<?php

namespace App\Http\Controllers\Admin;

use App\Admin\User as Staff;
use App\Department;
use App\Training;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Image\Image;
use Spatie\Image\Manipulations;

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
        $this->updatedMessage = 'All settings have been successfully saved.';
        $this->settings = collect([
            [
                'title' => __('General'),
                'slug' => 'general',
                'description' => __('General settings such as, site title, site description, address and so on.'),
                'icon' => 'fas fa-cog',
                'url' => route('admin.setting.general.index'),
            ],
            [
                'title' => __('Role'),
                'slug' => 'role',
                'description' => __('Account role settings, such as supersu, admin, and user.'),
                'icon' => 'fas fa-users-cog',
                'url' => route('admin.setting.role.index'),
            ],
            [
                'title' => __('Permission'),
                'slug' => 'permission',
                'description' => __('Role permission settings, such as manage data, CRUD, and approval.'),
                'icon' => 'fas fa-th-list',
                'url' => '#',
            ],
            [
                'title' => __('Form'),
                'slug' => 'form',
                'description' => __('Form settings, such as open or close the form.'),
                'icon' => 'fas fa-stopwatch',
                'url' => route('admin.setting.form.index'),
            ],
            [
                'title' => __('Training'),
                'slug' => 'training',
                'description' => __('Training settings, such as quota limit, and payment nominal.'),
                'icon' => 'fas fa-business-time',
                'url' => route('admin.setting.training.index'),
            ],
            [
                'title' => __('Exam Readiness'),
                'slug' => 'exam_readiness',
                'description' => __('Exam readiness settings, such as reference school.'),
                'icon' => 'fas fa-book-reader',
                'url' => route('admin.setting.exam.readiness.index'),
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
            'navs' => $this->settings,
            'setting' => $this->settings->where('slug', 'training')->first(),
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
            $filename = 'logo_'.date('d_m_Y_H_i_s_').md5(uniqid(rand(), true)).'.'.$request->site_logo->extension();
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
            'navs' => $this->settings,
            'setting' => $this->settings->where('slug', 'training')->first(),
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
            'navs' => $this->settings,
            'setting' => $this->settings->where('slug', 'training')->first(),
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
                $request->merge([$formSetting->time_limit_slug => date('Y-m-d H:i:s', strtotime($request->{$formSetting->time_limit_slug}))]);
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
        if ( ! auth()->guard('admin')->user()->can('access training ' . $this->table)) {
            return redirect()->route('admin.setting.index')->with('alert-danger', __($this->noPermission));
        }

        $trainings = json_decode(setting('training_settings'));
        $implementations = Department::pluck('abbreviation', 'abbreviation')->toArray();
        $registerredCount = [];
        $registerredSum = [];

        foreach ($trainings as $training){
            foreach ($implementations as $implementation){
                $count = Training::registerredCount($training->name, $implementation, setting($training->setting_created_at_slug));
                $registerredCount += [$implementation => $count];
                if  ( ! next($implementations)){
                    $registerredCount += ["total" => array_sum($registerredCount)];
                }
            }
            // dd($registerredCount);
            $registerredSum += [$training->name => $registerredCount];

        }
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
            'navs' => $this->settings,
            'setting' => $this->settings->where('slug', 'training')->first(),
            'forms' => $trainings,
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
            'schoolImplementations' => $implementations,
            'registerredSum' => $registerredSum,
        ];
        return view('admin.setting.training.index', $view);
    }

    /**
     * Save form settings into database
     */
    public function trainingStore(Request $request)
    {
        if ( ! auth()->guard('admin')->user()->can('access training ' . $this->table)) {
            return redirect()->route('admin.setting.index')->with('alert-danger', __($this->noPermission));
        }
        foreach (json_decode(setting('training_settings')) as $trainingSetting) {
            if (setting($trainingSetting->quota_limit_slug) != $request->{$trainingSetting->quota_limit_slug}) {
                $request->request->add([$trainingSetting->setting_created_at_slug => now()->toDateTimeString()]);
            }
            if ($request->filled($trainingSetting->time_limit_slug)) {
                $request->merge([$trainingSetting->time_limit_slug => date('Y-m-d H:i:s', strtotime($request->{$trainingSetting->time_limit_slug}))]);
            }
            if  (setting($trainingSetting->school_level_slug) != $request->{$trainingSetting->school_level_slug}){
                $request->merge([$trainingSetting->school_level_slug => json_encode($request->{$trainingSetting->school_level_slug})]);
            }
            if  (setting($trainingSetting->school_implementation_slug) != $request->{$trainingSetting->school_implementation_slug}){
                $request->merge([$trainingSetting->school_implementation_slug => json_encode($request->{$trainingSetting->school_implementation_slug})]);
            }
            dd($request->{$trainingSetting->school_implementation_slug});
        }
        setting($request->except(['_token']))->save();
        return redirect(url()->previous())->with('alert-success', __($this->updatedMessage));
    }

    /**
     * Show exam readiness settings page
     */
    public function examReadiness()
    {   
        if ( ! auth()->guard('admin')->user()->can('access exam_readiness ' . $this->table)) {
            return redirect()->route('admin.setting.index')->with('alert-danger', __($this->noPermission));
        }
        $view = [
            'back' => route('admin.setting.index'),
            'title' => __('Exam Readiness Settings'),
            'breadcrumbs' => [
                route('admin.setting.index') => __('Setting'),
                route('admin.setting.exam.readiness.index') => __('Exam Readiness'),
                null => __('Edit')
            ],
            'subtitle' => __('All About Exam Readiness Settings'),
            'description' => __('You can adjust all exam readiness settings here'),
            'navs' => $this->settings,
            'setting' => $this->settings->where('slug', 'training')->first(),
            'examReadinesses' => json_decode(setting('exam_readiness_settings')),
            'departments' => Department::orderBy('name', 'asc')->pluck('name', 'abbreviation')->toArray(),
        ];
        return view('admin.setting.exam.readiness.index', $view);
    }

    /**
     * Save exam readiness settings into database
     */
    public function examReadinessStore(Request $request)
    {
        if ( ! auth()->guard('admin')->user()->can('access form ' . $this->table)) {
            return redirect()->route('admin.setting.index')->with('alert-danger', __($this->noPermission));
        }
        setting($request->except(['_token']))->save();
        return redirect(url()->previous())->with('alert-success', __($this->updatedMessage));
    }
}
