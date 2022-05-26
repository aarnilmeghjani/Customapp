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
use phpDocumentor\Reflection\PseudoTypes\NonEmptyLowercaseString;

class Importcustomerdata implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $importData_arr;
    public $email;

    public function __construct($importData_arr,$email)
    {
        $this->importData_arr = $importData_arr;
        $this->email = $email;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info($this->importData_arr);
//        Log::info($reason);
        $successInsertCount = 0;
        $totalcustomer=sizeof($this->importData_arr)-1;
//        $reason=[];

        foreach (array_slice($this->importData_arr, 1) as $importData) {
            $successInsertCount++;

            $i = 0;
            $insertData = array(
                "name" => $importData[1],
                "email" => $importData[2],
                "phone" => $importData[3],
                "address" => $importData[4]);


            customer::updateOrCreate(
                ['email' => $insertData['email']],
                ['name' => $insertData['name'], 'phone' => $insertData['phone'], 'address' => $insertData['address']]
            );
        }
            $mailData = [];
            $mailData['heading'] = "customer Import Report - ";
            $mailData['totalDealer'] = $totalcustomer;
            $mailData['totalSuccessDealer'] = $successInsertCount;
            $mailData['totalFailDealer'] = $totalcustomer - $successInsertCount;
//            $mailData['errorMsg'] = $errorMsg;


             Mail::to($this->email)->send(new ImportData("Report of Import customer data",$mailData,'email.import.data'));



//            $value = customer::where('email', $insertData['email'])->get();
//
//            if ($value->count() == 0) {
//                $successInsertCount++;
//                customer::insert($insertData);
//            } else {
//                $notInsertCount++;
//                $reason[$i]['email'] = $insertData['email'];
//                $reason[$i]['reason'] = 'not inserted due to delicate email ';
//            }




//        Log::info($reason);

    }
}
