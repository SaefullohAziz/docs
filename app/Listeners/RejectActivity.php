<?php

namespace App\Listeners;

use App\Activity;
use App\Events\ActivityRejected;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class RejectActivity
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
     * @param  ActivityRejected  $event
     * @return void
     */
    public function handle(ActivityRejected $event)
    {
        $this->saveStatusBatch('Rejected', 'Menolak pengajuan aktivitas / kegiatan.', $event->request);
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
            $activity = Activity::find($id);
            saveStatus($activity, $status, $desc);
        }
    }
}
