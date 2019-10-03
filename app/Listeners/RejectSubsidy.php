<?php

namespace App\Listeners;

use App\Subsidy;
use App\Status;
use App\Events\SubsidyRejected;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class RejectSubsidy
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
     * @param  SubsidyRejected  $event
     * @return void
     */
    public function handle(SubsidyRejected $event)
    {
        $this->saveStatusBatch('Rejected', 'Menolak pengajuan subsidi.', $event->request);
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
            $subsidy = Subsidy::find($id);
            saveStatus($subsidy, $status, $desc);
        }
    }
}
