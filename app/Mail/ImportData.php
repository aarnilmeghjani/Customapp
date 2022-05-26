<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ImportData extends Mailable
{
    use Queueable, SerializesModels;
    public $subject,$mailData,$bladeView;

    /**
     * Create a new message instance.
     *
     * @param $subject
     * @param $mailData
     * @param $bladeView
     */
    public function __construct($subject,$mailData,$bladeView)
    {
        $this->subject=$subject;
        $this->mailData=$mailData;
        $this->bladeView=$bladeView;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $data=$this->mailData;
        return $this->markdown($this->bladeView,compact('data'))->subject($this->subject);
    }
}
