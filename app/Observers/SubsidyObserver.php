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
        saveStatus($subsidy, 'Created', 'Membuat pengajuan program bantuan.');
    }

    /**
     * Handle the subsidy "updated" event.
     *
     * @param  \App\Subsidy  $subsidy
     * @return void
     */
    public function updated(Subsidy $subsidy)
    {
        saveStatus($subsidy, 'Edited', 'Mengubah pengajuan program bantuan.');
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
}
