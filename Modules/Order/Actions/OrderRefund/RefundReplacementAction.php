<?php

namespace Modules\Order\Actions\OrderRefund;

use Modules\Order\Enums\OrderEnum;
use Modules\Order\Entities\Order;
use Modules\CoreData\Entities\City;
use Modules\Acl\Entities\Dropshipper;
use Modules\CoreData\Entities\Country;
use Modules\Order\Entities\OrderRefund;
use Modules\Order\Service\OrderStatusRefundService;
use Modules\Order\Repositories\OrderRefundRepository;
use Modules\Order\Actions\Order\AymakanCreateShipmentOrderAction;

class RefundReplacementAction
{
    protected $repo;

    /**
     * Create a new Repository instance.
     *
     * @return void
     */
    public function __construct(OrderRefundRepository $repository)
    {
        $this->repo = $repository;
    }

    /**
     * This function executes an orderRefund by calculating the total quantity, total price, total VAT, cost
     * price, weight, and shipping fees, and then saves the orderRefund data.
     * 
     * param Request request The  parameter is an instance of the Request class, which is
     * typically used to retrieve data from the HTTP request. It contains information such as the
     * request method, headers, and input data.
     * 
     * @return a boolean value. If the data is successfully saved, it will return true. Otherwise, it
     * will return false.
     */
    public function execute($request, $id)
    {
        $orderRefund = OrderRefund::find($id);
        $orderRefund->status_id = OrderEnum::REFUND_REPLACEMENT_STATUS;

        if ($orderRefund->save()) {
            app()->make(OrderStatusRefundService::class)->store($orderRefund);
            $data = Order::find($orderRefund->order_id);
            $city = City::find($data->customerCity);
            $country = Country::find($data->country_id);
            $dropShopping = Dropshipper::find($data->dropshipper_id);
            $requestAymakan = [
                'requested_by' => isset($dropShopping->first_name) ? $dropShopping->first_name . ' ' . $dropShopping->second_name : $dropShopping->id,
                'declared_value' => $orderRefund->grandTotal + $data->shippingFees,
                'delivery_name' => $data->customerName,
                'declared_value_currency' => 'SAR',
                "reference" => 'orderItemId-' . $orderRefund->id . '-' . rand(1, 999),
                "is_cod" => $data->paymentMethod,
                "cod_amount" => ($data->paymentMethod == 2) ? $orderRefund->grandTotal + $data->shippingFees : $data->shippingFees,
                'currency' => 'SAR',
                "delivery_neighbourhood" => $data->customerAddress,
                "delivery_postcode" => '1234',
                "delivery_description" => "",
                'delivery_city' => $city->name?->value,
                'delivery_address' => $data->customerAddress,
                'delivery_country' =>  "SA",
                'delivery_phone' =>  $data->customerPhone,
                'collection_name' =>  $dropShopping->store_name ?? ($dropShopping->first_name ?? $dropShopping->id) . ' ' . $dropShopping->second_name,
                'collection_city' =>  "Riyadh",
                "collection_neighbourhood" => 'neighbourhood',
                'collection_address' =>  'address',
                'collection_country' =>  "SA",
                "collection_postcode" => '1234',
                "collection_description" => "",
                "weight" => $data->weight ?? 1,
                'collection_phone' =>  $dropShopping->phone,
                'pieces' =>  $orderRefund->countOrderItem,
                'items_count' =>  $orderRefund->totalQuantity,
                // data error
                'invoice_number' => $orderRefund->id,
                'invoice_date' => $orderRefund->created_at->format('Y-m-d'),
                'line_items' => 'test test test',
            ];
            $aymakan = App(AymakanCreateShipmentOrderAction::class)->execute($requestAymakan);
            $orderRefund->tracking_number = $aymakan['shipping']['tracking_number'];
            $orderRefund->pdf_label = $aymakan['shipping']['pdf_label'];

            return $orderRefund->save();
        }
    }
}
