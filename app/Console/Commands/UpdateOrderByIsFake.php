<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Modules\Order\Entities\Order;

class UpdateOrderByIsFake extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:UpdateOrderByIsFake';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Order By Is Fake';

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

        $orders = Order::pluck('customerPhone');
        
        foreach ($orders as $order) {
            app('Modules\Order\Actions\Order\FakeNumberOrderAction')->execute($order);
        }
    }
}
