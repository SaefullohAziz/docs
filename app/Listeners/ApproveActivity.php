<?php

namespace App\Listeners;

use App\Activity;
use App\Status;
use App\Events\ActivityApproved;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ApproveActivity
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
     * @param  ActivityApproved  $event
     * @return void
     */
    public function handle(ActivityApproved $event)
    {
        $this->saveStatusBatch('Approved', 'Menyetujui pengajuan activity.', $event->request);
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
            $log = actlog($desc);
            $status = Status::byName($status)->first();
            $activity->status()->attach($status->id, [
                'log_id' => $log,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
