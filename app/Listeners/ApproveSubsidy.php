<?php

namespace App\Listeners;

use App\Subsidy;
use App\Status;
use App\Events\SubsidyApproved;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ApproveSubsidy
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
     * @param  SubsidyApproved  $event
     * @return void
     */
    public function handle(SubsidyApproved $event)
    {
        $this->saveStatusBatch('Approved', 'Menyetujui pengajuan subsidi.', $event->request);
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
