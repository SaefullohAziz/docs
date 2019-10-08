<?php

if ( ! function_exists('hasPermission')) {

    /**
     * Save activity log
     *
     * @param
     * @return
     */
    function hasPermission($desc, $redirect = 'home', $alert = 'You have no related permission.', $guard = 'admin')
    {
        if (auth()->guard($guard)->user()->can($desc)) {
            return redirect()->route('admin.home')->with('alert-danger', __($alert));
        }
    }
}

if ( ! function_exists('actlog')) {

    /**
     * Save activity log
     *
     * @param
     * @return
     */
    function actlog($desc)
    {
        $data = [
            'description' => $desc,
            'created_by' => 'school',    
        ];
        if (auth()->guard('admin')->check()) {
            $data['created_by'] = 'admin';
            $addonData = [
                'staff_id' => auth()->guard('admin')->user()->id
            ];
            $data = array_merge($data, $addonData);
        } elseif (auth()->guard('web')->check()) {
            $addonData = [
                'user_id' => auth()->guard('web')->user()->id
            ];
            $data = array_merge($data, $addonData);
        }
        $log = App\ActivityLog::create($data);
        return $log->id;
    }
}

if ( ! function_exists('saveStatus')) {
    /**
     * Save related data status
     * 
     * @param  \App\Model $model  Related model data
     * @param  string $status Name of status
     * @param  string $desc   Description for log
     */
    function saveStatus($model, $status, $desc, $addonData = [])
    {
        $log = actlog($desc);
        $status = App\Status::byName($status)->first();
        $data = [
            'log_id' => $log,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $data = array_merge($data, $addonData);
        $model->status()->attach($status->id, $data);
    }
}

if ( ! function_exists('schoolYear')) {

    /**
     * Show valid school year
     *
     * @param
     * @return
     */
    function schoolYear($date = null)
    {
        if (empty($date)) {
            $date = date('Y-m-d');
        }
        $schoolYear = date('Y', strtotime($date)).'/'.date('Y', strtotime($date . ' +1 year'));
        $month = date('n', strtotime($date));
        if ($month < 6) {
            $schoolYear = date('Y', strtotime($date . ' -1 year')).'/'.date('Y', strtotime($date));
        }
        return $schoolYear;
    }
}

if ( ! function_exists('studentGeneration')) {

    /**
     * Show valid current student generation
     *
     * @param
     * @return
     */
    function studentGeneration($school, $department)
    {
        $generation = 'Angkatan 1';
        $class = App\StudentClass::where('school_id', $school->id)
                                                                ->where('department_id', $department->id)
                                                                ->latest()
                                                                ->first();
        if ($class) {
            $generation = $class->generation;
            if ($class->school_year != schoolYear()) {
                $generation = 'Angkatan ' . ((substr($class->generation, -1)) + 1);
            }
        }
        return $generation;
    }
}
