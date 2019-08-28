<?php

namespace App\Mail;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class TrainingApproved extends Mailable
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
        return $this->markdown('mail.training.approved')
                ->to('ahmad.husen@mitraabadi.com')
                ->with([
                    'school' => $this->training->school->name,
                    'type' => $this->training->type,
                    'bookingCode' => $this->training->booking_code,
                    'pic' => $this->training->pic[0]->name,
                    'nominal' => number_format(3000000, 2, ',', '.'),
                    'bookingTime' => Carbon::parse($this->training->created_at)->format('d-m-Y H:i:s'),
                    'expiredTime' => Carbon::parse($this->training->created_at)->addHour(3)->format('d-m-Y H:i:s'),
                    // Bank Account
                    'bank' => 'Mandiri',
                    'bankAccountNumber' => '132-007-003-0003',
                    'bankAccountOnBehalfOf' => 'PT. Mabito Karya'
                ]);
    }
}
