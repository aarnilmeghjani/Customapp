<?php

namespace App\Console\Commands;

use App\Models\customer;
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
        $customer = new customer;
        $customer->name = 'name';
        $customer->email = 'aarnil@hulkapps.com';
        $customer->phone = 8488098511;
        $customer->address = 'surat';
        $customer->save();
        return 0;
    }
}
