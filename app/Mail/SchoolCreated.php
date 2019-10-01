<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SchoolCreated extends Mailable
{
    use Queueable, SerializesModels;

    protected $school;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($school)
    {
        $this->school = $school;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('mail.school.created')
                ->to([$this->school->school_email, $this->school->headmaster_email, $this->school->pic[0]->email])
                ->with([
                    'url' => url('/'),
                    'code' => $this->school->code
                ]);
    }
}