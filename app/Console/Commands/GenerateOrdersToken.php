<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Modules\Order\Entities\Order;

class GenerateOrdersToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-orders-token';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate token for orders that do not have one';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Order::whereNull('token')->chunk(100, function ($orders) {
            foreach ($orders as $order) {
                $token = $this->generateToken(10);

                $order->update(['token' => $token]);
                $this->info("Order ID: {$order->id} - Token: {$token}");
            }
        });
    }

    private function generateToken($length)
    {
        $token = substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, $length);

        if (Order::where('token', $token)->exists()) {
            return $this->generateToken($length);
        }

        return $token;
    }
}
