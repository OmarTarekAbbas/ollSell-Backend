<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ScriptValidationOperator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:ScriptValidationOperator';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Script Validation Operator';

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
        app('Modules\Order\Http\Controllers\OrderListController')->scriptValidationOperator();
    }
}
