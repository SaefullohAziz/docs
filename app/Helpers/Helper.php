<?php

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
