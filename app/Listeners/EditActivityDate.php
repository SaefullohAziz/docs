<?php

namespace App\Listeners;

use App\Activity;
use App\Events\ActivityDateEdited;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class EditActivityDate
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
     * @param  ActivityDateEdited  $event
     * @return void
     */
    public function handle(ActivityDateEdited $event)
    {
        Activity::where('type', 'Kunjungan Industri')->whereIn('id', $event->request->selectedData)->update(['date' => date('Y-m-d', strtotime($event->request->date))]);
    }
}
