<?php

namespace App\Listeners;

use App\Training;
use App\Status;
use App\Events\TrainingCanceled;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CancelTraining
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
     * @param  TrainingCanceled  $event
     * @return void
     */
    public function handle(TrainingCanceled $event)
    {
        $this->saveStatusBatch('Canceled', 'Membatalkan pendaftaran training.', $event->request);
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
            // If related training is 'approved', 'processed', or 'canceled'
            if ($this->statusCheck($id) >= 4) {
                $training = Training::find($id);
                $log = actlog($desc);
                $status = Status::byName($status)->first();
                $training->status()->attach($status->id, [
                    'log_id' => $log,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    /**
     * Check status
     * 
     * @param  \App\Training  $training
     */
    public function statusCheck($id)
    {
        $training = Training::with(['latestTrainingStatus.status'])->where('id', $id)->first();
        $statuses = ['Created', 'Edited', 'Processed', 'Canceled', 'Approved', 'Payment', 'Paid', 'Participant'];
        $statuses = array_slice($statuses, array_search($training->latestTrainingStatus->status->name, $statuses));
        return count($statuses);
    }
}
