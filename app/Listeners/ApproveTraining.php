<?php

namespace App\Listeners;

use App\School;
use App\Training;
use App\Status;
use App\Events\TrainingApproved;
use App\Notifications\TrainingApproved as ApprovedTraining;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ApproveTraining
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
     * @param  TrainingApproved  $event
     * @return void
     */
    public function handle(TrainingApproved $event)
    {
        $this->sendNotification($event->request);
        $this->saveStatusBatch('Approved', 'Menyetujui pendaftaran training.', $event->request);
        $this->saveBatch($event->request);
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

     /**
     * Save batch
     * 
     * @param  \Illuminate\Http\Request  $request
     */
    public function saveBatch($request)
    {
        foreach ($request->selectedData as $id) {
            $training = Training::find($id);
            $training->batch = $request->batch;
            $training->save();
        }
    }

    /**
     * Send notification
     * 
     * @param  \Illuminate\Http\Request  $request
     */
    public function sendNotification($request)
    {
        foreach ($request->selectedData as $id) {
            $training = Training::find($id);
            $school = School::findOrFail($training->school->id);
            if ($training->batch == 'Waiting') {
                $school->notify(new ApprovedTraining($training));
            }
        }
    }
}
