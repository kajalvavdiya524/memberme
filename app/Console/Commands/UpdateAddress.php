<?php

namespace App\Console\Commands;

use App\Address;
use Illuminate\Console\Command;

class UpdateAddress extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:address';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        Address::chunk(200, function ($addresses){
            foreach ($addresses as $address) {
                /** @var $address Address */
                $address1 = str_replace($address->city, '', $address->address1);
                $address1 = str_replace(', '.$address->suburb.',', '', $address1);
                $address1 = str_replace($address->postal_code, '', $address1);
                $address->address2 =  $address1;
                $address->save();
            }
        });
    }
}
