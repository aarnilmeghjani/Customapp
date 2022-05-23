<?php

namespace App\Console\Commands;

use App\Models\Order;
use Illuminate\Console\Command;

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
        $orderfulfil =Order::where('order_id','4578960474311')->get();

        $orderfulfil[0]->fulfillment_status ='fulfilled';
        $orderfulfil[0]->save();
        return 0;
    }
}
