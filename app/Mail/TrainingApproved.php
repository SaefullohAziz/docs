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
                ->to(array_merge([$this->training->school->school_email, $this->training->pic[0]->email], $this->training->participants->pluck('email')->toArray()))
                ->cc('informasi@axiooclassprogram.com', 'ACP')
                ->bcc('ahmad.husen@mitraabadi.com', 'Ahmad Husen')
                ->with([
                    'school' => $this->training->school->name,
                    'type' => $this->training->type,
                    'bookingCode' => $this->training->booking_code,
                    'pic' => $this->training->pic[0]->name,
                    'nominal' => number_format($this->training->payment[0]->total, 2, ',', '.'),
                    'bookingTime' => Carbon::parse($this->training->created_at)->format('d-m-Y H:i:s'),
                    'expiredTime' => Carbon::parse($this->training->created_at)->addHour(3)->format('d-m-Y H:i:s'),
                    // Bank Account
                    'bank' => 'Mandiri',
                    'bankAccountNumber' => '132-007-003-0003',
                    'bankAccountOnBehalfOf' => 'PT. Mabito Karya'
                ]);
    }
}
