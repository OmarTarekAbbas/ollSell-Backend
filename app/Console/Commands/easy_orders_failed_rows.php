<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Order\Exports\Order\MissingOrdersExport;

class easy_orders_failed_rows extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'easy_order:failed';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'create easy order failed sheet';

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
        $files = public_path('/missings/easy_order/' . today()->format('Y-m-d') . '/orders_failed_rows.csv');
        if(!is_file($files))
        {
            Excel::store(
                new MissingOrdersExport([]),
                'easy_order/' . today()->format('Y-m-d') . '/orders_failed_rows.csv',
                'public_missings'
            );
        }
        $files = public_path('/missings/easy_order/' . today()->addDay(1)
                ->format('Y-m-d') . '/orders_failed_rows.csv');
        if(!is_file($files))
        {
            Excel::store(
                new MissingOrdersExport([]),
                'easy_order/' . today()->addDay(1)->format('Y-m-d') . '/orders_failed_rows.csv',
                'public_missings'
            );
        }
    }
}
