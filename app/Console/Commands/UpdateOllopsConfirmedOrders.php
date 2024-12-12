<?php

namespace App\Console\Commands;

use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Modules\Order\Entities\Order;
use Illuminate\Support\Facades\Log;
use App\Services\OllopsClientService;

class UpdateOllopsConfirmedOrders extends Command
{
    protected $signature = 'order:update-node-status';
    protected $description = 'Update order status in Node.js application';

    protected $ollopsClient;

    public function __construct()
    {
        parent::__construct();
        $this->ollopsClient = app(OllopsClientService::class);
    }

    public function handle()
    {
        $orders = Order::where('validated_by', 'system')
            ->whereNotNull('ollops_confirmation_status')
            ->get();

        foreach ($orders as $order) {
            $this->updateOrderStatusInOllops($order);
        }

        $this->info('Order statuses updated successfully.');
    }

    protected function updateOrderStatusInOllops($order)
    {
        $payload = [
            'orderId' => $order->id,
            'status' => 'confirmed_by_system',
        ];

        try {
            $response = $this->ollopsClient->updateOrderStatus($payload);

            if ($response->getStatusCode() == 200) {
                $this->info("Order #{$order->id} status updated in Node.js successfully.");
            } else {
                Log::warning("Failed to update order #{$order->id} status in Node.js. Status code: {$response->getStatusCode()}");
            }
        } catch (\Exception $e) {
            Log::error("Exception occurred while updating order #{$order->id} status in Node.js: " . $e->getMessage());
        }
    }
}
