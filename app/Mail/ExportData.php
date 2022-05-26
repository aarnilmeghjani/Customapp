<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ExportData extends Mailable
{
    use Queueable, SerializesModels;
    public $subject,$data,$file,$fileName;

    /**
     * Create a new message instance.
     *
     * @param $subject
     * @param $data
     * @param $file
     * @param $fileName
     */
    public function __construct($subject,$data,$file,$fileName)
    {
        $this->subject=$subject;
        $this->data=$data;
        $this->file=$file;
        $this->fileName=$fileName;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $data['heading']=$this->data;
        return $this->markdown('email.export.data',compact('data'))->subject($this->subject)->attachData(stream_get_contents($this->file), $this->fileName);
    }
}
