<?php

namespace App\Console\Commands;

use App\Models\customer;
use App\Models\Order;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CheckTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test command to check functionality';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {


//        DB::beginTransaction();

        try {

            $customer = new customer;
            $customer->name = 'kirtan';
            $customer->email = 'kirtan@gmail.com';
            $customer->phone = '8956234512';
            $customer->address = 'vadodara';
            $customer->save();



            DB::commit();
            // all good
        } catch (\Exception $e) {
            dd('dukfvbhds');

            DB::rollback();
            dd('dukfvbhds');
        }
    }
}
