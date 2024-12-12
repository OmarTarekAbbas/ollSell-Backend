<?php

namespace Modules\Order\Http\Resources\Order;

use Carbon\Carbon;
use Modules\Order\Enums\ClickPayEnum;
use Modules\Order\Enums\OrderEnum;
use Modules\Finance\Enums\ProfitEnum;
use Modules\Order\Entities\orderRefund;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Order\Enums\PaymentEnum;
use Modules\Order\PaymentMethod\PaymentMethodList;
use Modules\CoreData\Http\Resources\City\CityResource;
use Modules\CoreData\Http\Resources\Status\StatusResource;
use Modules\CoreData\Http\Resources\Country\CountryListResource;
use Modules\Acl\Http\Resources\Dropshipper\DropshipperBranchResource;

class OrderResource extends JsonResource
{
    /**
     * It returns the data in the form of an array.
     *
     * param request The incoming request.
     *
     * return array
     */
    public function toArray($request)
    {
        $invoiceLink = setting('app_debug') == 'live' ? 'https://ollops.com/order-pay/' . $this->token  : 'https://beta-app.ollops.com/order-pay/' . $this->token;
        return [
            'id' => $this->id,
            'linkPdf' => is_array($this->invoice->toArray()) && !empty($this->invoice->toArray()) ? $invoiceLink : null,
            'checkOutId' => json_decode($this->checkOutId, true),
            'paymentMethod' => $this->paymentMethod ?? "",
            'paymentMethodData' => $this->setPaymentMethodText($this->paymentMethod),
            'shippingMethod' => $this->shippingMethod ?? "",
            'totalQuantity' => $this->totalQuantity ?? 0,
            'countOrderItem' => $this->countOrderItem ?? 0,
            'orderItem' => OrderItemResource::collection($this->orderItems),
            'shippingFees' => $this->shippingFees ?? 0,
            'shippingFeesText' => $this->shippingFees . currency() ?? 0,
            'grandTotal' => round($this->grandTotal, 2) ?? 0,
            'grandTotalText' =>  $this->grandTotal . currency() ?? 0,
            'subTotal' =>  round($this->subTotal, 2) ?? 0,
            'subTotalText' =>  $this->subTotal . currency() ?? 0,
            'dropshipperId' => $this->dropshipper_id,
            'dropshipperBranch' => new DropshipperBranchResource($this->dropshipperBranch),
            'status' => new StatusResource($this->status),
            'statusLog' => OrderStatusResource::collection($this->orderStatus),
            'customerName' => $this->customerName ?? "",
            'customerPhone' => $this->customerPhone ?? "",
            'phoneCode' => $this->phone_code ?? "",
            'customerAddress' => $this->customerAddress ?? "",
            'customerLocation' => $this->customerLocation ?? "",
            'country' => new CountryListResource($this->country),
            'deliveryDate' => $this->deliveryDate,
            'cancelDate' => $this->cancelDate,
            'orderDate' => $this->created_at->format('Y-m-d H:i:s'),
            'customerCity' => new CityResource($this->city),
            'weight' => $this->weight ?? 0,
            'tracking_number' => $this->tracking_number ?? '',
            'pdf_label' => $this->pdf_label ?? '',
            'setCanReturnOrder' => $this->setCanReturnOrder($this->created_at),
            'wasRefunded' => $this->wasRefunded(),
            'refunds' => $this->refunds,
            'district' => $this->district,
            'net_profit' => $this->net_profit,
            'cost_price' => $this->costPrice,
            'source_platform' => $this->source_platform,
            'status_number' => $this->status_number($this->status_id),
            'cancellation_reason' => $this->getCancellationReason(),
            'earning_status' => $this->status_id == OrderEnum::COMPLETED_STATUS ? ProfitEnum::status($this->transaction->isStatus, $this->transaction) : null,
            'canEdit' => $this->getCanEdit(),
            'canPay' => $this->canPay($this),
            'vat' => $this->getOrderVat(),
            'allow_discounts' => $this->allow_discounts,
            'applied_discount' => $this->applied_discount,
        ];
    }

    private function getOrderVat()
    {
        $vat = 0;
        foreach($this->orderItems as $item) {
            $vat += $item->vat_profit;
        }
        return $vat;
    }

    /**
     * Method getCanEdit
     *
     * return boolean
     */
    private function getCanEdit()
    {
        if ($this->status_id == OrderEnum::NEW_STATUS || $this->status_id == OrderEnum::PENDING_STATUS) {
            return true;
        }

        return false;
    }

    /**
     * Method getCancellationReason
     *
     * return string
     */
    private function getCancellationReason()
    {
        if ($this->status_id == OrderEnum::CANCELED_STATUS) {
            return $this->remark?->name;
        }
        return null;
    }

    /**
     * Method setPaymentMethodText
     *
     * return void
     */
    private function setPaymentMethodText($paymentMethod)
    {
        return PaymentMethodList::list()->where('id', $paymentMethod)->first();
    }

    /**
     * This function checks if an order can be returned for a refund based on its status and the number
     * of days since its creation.
     *
     * return a boolean value. If the conditions are met, it will return true, indicating that a
     * refund can be made. Otherwise, it will return false, indicating that a refund cannot be made.
     */
    private function setCanReturnOrder()
    {
        if ($this->status_id !== OrderEnum::COMPLETED_STATUS) return false;
        if ($this->wasRefunded()) return false;

        $returnOrderDays = 7; // make by setting
        $timeAfterAdminFromOrderCreation = (new Carbon(Carbon::parse($this['deliveryDate'])->toDateTime()))->addDay($returnOrderDays);

        if (date('Y-m-d H:i:s') < $timeAfterAdminFromOrderCreation->toDateTimeString()) {
            return true; // yes refund
        }

        return false; // no refund
    }

    /**
     * The function checks if an order has been refunded by checking if there is a record in the
     * orderRefund table with the given order ID.
     *
     * return a boolean value. It returns true if there is a record in the "orderRefund" table with
     * the given order_id, and false otherwise.
     */
    private function wasRefunded()
    {
        if (orderRefund::where('order_id', $this->id)->exists()) {
            return true;
        }
        return false;
    }

    private function status_number($id)
    {
        switch ($id) {
            case 1:
            case 11:
                $number = 0;
                break;
            case 2:
                $number = 1;
                break;
            case 13:
            case 20:
                $number = 2;
                break;
            case 14:
            case 10:
                $number = 3;
                break;
            case 4:
                $number = 4;
                break;
            case 3:
            case 5:
            case 6:
            case 7:
            case 8:
            case 9:
            case 12:
            case 15:
                $number = 5;
                break;
            default:
                $number =  0;
                break;
        }
        return $number;
    }

    function canPay($order)
    {
        $can = false;
        switch (true) {
            case $order->status_click_payment != ClickPayEnum::Pay && $order->paymentMethod == PaymentEnum::ONLINE_METHOD_ID:
            case in_array($order->status_id, [OrderEnum::NEW_STATUS, OrderEnum::PENDING_STATUS, OrderEnum::PAY_PENDING_STATUS,OrderEnum::ONHOLD_STATUS] ):
                $can = true;
                break;
            default:
                $can = false;
                break;
        }
        return $can;
    }
}
