<?php

namespace Modules\Order\Actions\OrderStatus;

use Modules\Order\Entities\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Store\Entities\DropshipperMappingOrder;
use Modules\StoreIntegrations\Http\Services\SallaService;

class ChangeOrderStatusInSallaAction
{
    /**
     * Update order status and shipment information in Salla if applicable.
     *
     * @param Order $order The order instance to be updated in Salla.
     * @return bool True if operation is successful, otherwise false.
     */
    public function execute(Order $order): bool
    {
        $mapping = $this->getDropshipperMapping($order);
        if (!$mapping) {
            return false;
        }

        $dropshipper = $this->getDropshipperToken($order->dropshipper_id);
        if (!$dropshipper) {
            return false;
        }

        $statusUpdated = $this->updateOrderStatusInSalla($order, $dropshipper, $mapping->model_id);
        $shipmentUpdated = $this->updateShipmentDetailsInSalla($order, $dropshipper, $mapping->model_id);

        return $statusUpdated || $shipmentUpdated;
    }

    /**
     * Retrieve the dropshipper mapping for a given order.
     *
     * @param Order $order
     * @return DropshipperMappingOrder|null
     */
    private function getDropshipperMapping(Order $order): ?DropshipperMappingOrder
    {
        return DropshipperMappingOrder::where('model_type', 'salla')
            ->where('order_id', $order->id)
            ->first();
    }

    /**
     * Retrieve the dropshipper token for a given dropshipper ID.
     *
     * @param int $dropshipperId
     * @return object|null
     */
    private function getDropshipperToken(int $dropshipperId): ?object
    {
        return DB::table('salla_tokens')
            ->where('dropshipper_id', $dropshipperId)
            ->first();
    }

    /**
     * Update order status in Salla if it has a corresponding status.
     *
     * @param Order $order
     * @param object $dropshipper
     * @param string $modelId
     * @return bool
     */
    private function updateOrderStatusInSalla(Order $order, object $dropshipper, string $modelId)
    {
        $statusSlug = Order::SALLA_ORDER_STATUS[$order->status_id] ?? null;
        if (!$statusSlug) {
            return false;
        }

        $payload = ['slug' => $statusSlug];
        $this->logStatusChange($order->status_id);

        return $this->getSallaClient()->updateOrderStatus($payload, $dropshipper, $modelId);
    }

    /**
     * Update shipment details in Salla if tracking number is available.
     *
     * @param Order $order
     * @param object $dropshipper
     * @param string $modelId
     * @return bool
     */
    public function updateShipmentDetailsInSalla(Order $order, object $dropshipper, string $modelId)
    {
        if (empty($order->tracking_number)) {
            return false;
        }

        $payload = [
            'shipment_number' => $order->tracking_number,
            'pdf_label' => $order->pdf_label,
            'tracking_link' => $order->pdf_label,
        ];

        return $this->getSallaClient()->updateOrderShipment($payload, $dropshipper, $modelId);
    }

    /**
     * Log order status change to Salla.
     *
     * @param int $statusId
     * @return void
     */
    private function logStatusChange(int $statusId): void
    {
        Log::channel('salla')->info("Change status: $statusId");
    }

    private function getSallaClient()
    {
        return app(SallaService::class);
    }
}
