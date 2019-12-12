<?php

namespace App\Observers;

use App\Grant;

class GrantObserver
{
    /**
     * Handle the grant "created" event.
     *
     * @param  \App\Grant  $grant
     * @return void
     */
    public function created(Grant $grant)
    {
        saveStatus($grant, 'Created', 'Membuat pengajuan hibah.');
    }

    /**
     * Handle the grant "updated" event.
     *
     * @param  \App\Grant  $grant
     * @return void
     */
    public function updated(Grant $grant)
    {
        saveStatus($grant, 'Edited', 'Mengubah pengajuan hibah.');
    }

    /**
     * Handle the grant "deleted" event.
     *
     * @param  \App\Grant  $grant
     * @return void
     */
    public function deleted(Grant $grant)
    {
        //
    }

    /**
     * Handle the grant "restored" event.
     *
     * @param  \App\Grant  $grant
     * @return void
     */
    public function restored(Grant $grant)
    {
        //
    }

    /**
     * Handle the grant "force deleted" event.
     *
     * @param  \App\Grant  $grant
     * @return void
     */
    public function forceDeleted(Grant $grant)
    {
        //
    }
}
