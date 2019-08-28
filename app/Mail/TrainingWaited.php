<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class TrainingWaited extends Mailable
{
    use Queueable, SerializesModels;

    protected $training;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($training)
    {
        $this->training = $training;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('mail.training.waited')
                ->with([
                    'school' => $this->training->school->name,
                ]);
    }
}
