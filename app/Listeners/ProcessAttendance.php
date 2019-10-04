<?php

namespace App\Listeners;

use App\Attendance;
use App\Status;
use App\Events\AttendanceProcessed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ProcessAttendance
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  AttendanceProcessed  $event
     * @return void
     */
    public function handle(AttendanceProcessed $event)
    {
        $this->saveStatusBatch('Processed', 'Memproses konfirmasi kehadiran.', $event->request);
    }

    /**
     * Save status batch
     * 
     * @param  string  $status
     * @param  string  $desc
     * @param  \Illuminate\Http\Request  $request
     */
    public function saveStatusBatch($status, $desc, $request)
    {
        foreach ($request->selectedData as $id) {
            $attendance = Attendance::find($id);
            saveStatus($attendance, $status, $desc);
        }
    }
}
