<?php

namespace App\Observers;

use App\Subsidy;
use App\Status;

class SubsidyObserver
{
    /**
     * Handle the subsidy "created" event.
     *
     * @param  \App\Subsidy  $subsidy
     * @return void
     */
    public function created(Subsidy $subsidy)
    {
        $this->saveStatus($subsidy, 'Created', 'Membuat pengajuan program bantuan.');
    }

    /**
     * Handle the subsidy "updated" event.
     *
     * @param  \App\Subsidy  $subsidy
     * @return void
     */
    public function updated(Subsidy $subsidy)
    {
        $this->saveStatus($subsidy, 'Edited', 'Mengubah pengajuan program bantuan.');
    }

    /**
     * Handle the subsidy "deleted" event.
     *
     * @param  \App\Subsidy  $subsidy
     * @return void
     */
    public function deleted(Subsidy $subsidy)
    {
        //
    }

    /**
     * Handle the subsidy "restored" event.
     *
     * @param  \App\Subsidy  $subsidy
     * @return void
     */
    public function restored(Subsidy $subsidy)
    {
        //
    }

    /**
     * Handle the subsidy "force deleted" event.
     *
     * @param  \App\Subsidy  $subsidy
     * @return void
     */
    public function forceDeleted(Subsidy $subsidy)
    {
        //
    }

    /**
     * Save status
     * 
     * @param  \App\Subsidy  $subsidy
     * @param  string  $status
     * @param  string  $desc
     */
    public function saveStatus($subsidy, $status, $desc)
    {
        $log = actlog($desc);
        $status = Status::byName($status)->first();
        $subsidy->status()->attach($status->id, [
            'log_id' => $log,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
