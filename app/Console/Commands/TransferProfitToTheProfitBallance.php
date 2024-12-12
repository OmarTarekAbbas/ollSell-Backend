<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TransferProfitToTheProfitBallance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:TransferProfitToTheProfitBallance';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Transfer Profit To The Profit Ballance';

    /**
     * Create a new command instance.
     *
     * return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * return mixed
     */
    public function handle()
    {
        app('Modules\Acl\Service\DropshipperService')->cronJopUpdateProfitBalance();
    }
}
