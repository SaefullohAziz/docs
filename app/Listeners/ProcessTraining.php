<?php

namespace App\Listeners;

use App\Training;
use App\Status;
use App\Events\TrainingProcessed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ProcessTraining
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
     * @param  TrainingProcessed  $event
     * @return void
     */
    public function handle(TrainingProcessed $event)
    {
        $this->saveStatusBatch('Processed', 'Memproses pendaftaran training.', $event->request);
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
            $training = Training::find($id);
            saveStatus($training, $status, $desc);
        }
    }
}
