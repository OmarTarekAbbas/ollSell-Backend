<?php

namespace Modules\Supplier\Actions\OrderItem;

use Modules\Order\Enums\OrderEnum;

use Illuminate\Http\Request;
use Modules\Order\Entities\Order;
use Modules\Order\Entities\OrderItem;
use Modules\Order\Repositories\OrderItemRepository;
//todo change
class SupplierUpdateOrderAction
{
    protected $id;
    protected $request;

    /**
     * Create a new Repository instance.
     *
     * @return void
     */
    public function __construct(Request $request, $id)
    {
        $this->id = $id;
        $this->request = $request;
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
        $this->request->merge([
            'status_id' => OrderEnum::READY_STATUS,
            'is_ready' => 1,
        ]);
        $orderItems = OrderItem::where('order_id', $this->id)->where('supplier_id', auth()->id())->get();
        foreach ($orderItems as $orderItem) {
            App(OrderItemRepository::class)->save($this->request, $orderItem->id);
        }
    }
}
