<?php

namespace Modules\Order\Actions\PendingOrder;

use Modules\Order\Entities\PendingOrder;
use Modules\Order\Entities\PendingOrderItem;
use Modules\Order\Repositories\PendingOrderItemRepository;

class CreatePendingOrderItemAction
{
    protected $pendingOrder;
    protected $import = false;

    /**
     * Create a new Repository instance.
     *
     * @return void
     */
    public function __construct(PendingOrder $pendingOrder)
    {
        $this->pendingOrder = $pendingOrder;
    }

    /**
     * This function executes an order by calculating the total quantity, total price, total VAT, cost
     * price, weight, and shipping fees, and then saves the order data.
     *
     * param Request request The  parameter is an instance of the Request class, which is
     * typically used to retrieve data from the HTTP request. It contains information such as the
     * request method, headers, and input data.
     *
     * @return a boolean value. If the data is successfully saved, it will return true. Otherwise, it
     * will return false.
     */
    public function execute()
    {
        if ($this->pendingOrder->id) {
            $orderItems = PendingOrderItem::where('pending_order_id', $this->pendingOrder->id)->get();

            foreach ($orderItems as $orderItem) {
                $orderItem->delete();
            }
        }
        $request = request();

        $items = $request->items;
        foreach ($items as $item) {
            $newRequest = new $request([
                'pending_order_id' => $this->pendingOrder->id,
                'sku' => $item['sku'] ?? null,
                'quantity' =>   (int)$item['quantity'] ?? null,
                'selling_price' =>   $item['selling_price'] ?? null,
            ]);
            $data = App(PendingOrderItemRepository::class)->save($newRequest);
        }

        if ($data) {
            return true;
        }

        return false;
    }
}
