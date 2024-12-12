<?php

namespace Modules\Order\Actions\Order;

use App\Services\OllopsClientService;
use Modules\Order\Enums\OrderEnum;

class SyncOllopsOrderAction
{
    protected $order;
    protected $oldStatus;
    protected $newStatus;
    protected $ollopsClient;

    public function __construct($order, $oldStatus, $newStatus)
    {
        $this->order = $order;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
    }


    public function execute()
    {
        $this->ollopsClient = app(OllopsClientService::class);

        if ($this->newStatus == OrderEnum::CANCELED_STATUS) {
            $this->informOllops('cancelled_by_system');
        } elseif ($this->newStatus == OrderEnum::PREPARING_STATUS) {
            $this->informOllops('confirmed_by_system');
        }
    }

    protected function informOllops($status)
    {
        $payload = [
            'orderId' => $this->order->id,
            'status' => $status,
        ];

        $response = $this->ollopsClient->updateOrderStatus($payload);

        if ($response->getStatusCode() == 200) {
            $responseData = json_decode($response->getBody()->getContents(), true);
            $this->order->update(['ollops_confirmation_status' => $status]);
        } else {
            // Handle error
        }
    }
}
