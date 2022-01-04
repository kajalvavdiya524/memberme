<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;

class CheckFinancial extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:financial';

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

//        echo 'Running Scheueler'.PHP_EOL;

    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $user = User::find(2);
        $user->first_name = rand(399,54444);
        $user->save();
        echo 'Running Scheueler'.PHP_EOL;
    }

}
