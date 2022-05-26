<?php

namespace App\Jobs;

use App\Mail\ImportData;
use App\Models\customer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class Exportdata implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
  public $email;

    /**
     * Create a new job instance.
     * @param $email
     */
    public function __construct($email)
    {
        $this->email=$email;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $data = customer::all();
        Log::info(json_encode($data,JSON_PRETTY_PRINT));

        $slug = time();
        $fileName = "customer_export_$slug.csv";

        $file = fopen('php://temp', 'w+');
        fputcsv($file, ["name", "email", "phone", "address"]);


        foreach ($data as $item) {
            fputcsv($file, [
                "name" => $item->name,
                "email" => $item->email,
                "phone" => $item->phone,
                "address" => $item->address,
            ]);
        }

        rewind($file);
        $data = " Export Customer Data Ready to Download, Please find attached file.";
        Mail::to($this->email)->send(new \App\Mail\ExportData(" Export customer data",$data,$file,$fileName));

        fclose($file);



    }
}
