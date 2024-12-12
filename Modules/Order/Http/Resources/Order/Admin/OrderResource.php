<?php

namespace Modules\Order\Http\Resources\Order\Admin;

use Carbon\Carbon;
use Modules\Order\Entities\Order;
use Modules\Order\Entities\OrderRefund;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Order\PaymentMethod\PaymentMethodList;
use Modules\CoreData\Http\Resources\City\CityResource;
use Modules\Order\Http\Resources\Order\OrderItemResource;
use Modules\CoreData\Http\Resources\Status\StatusResource;
use Modules\CoreData\Http\Resources\Country\CountryListResource;
use Modules\Order\Entities\Invoice;
use Modules\Order\Enums\OrderEnum;

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
        $attempts = $this->followUps()->where('activity_type', '!=', 'Initiated')->get();
        $firstAttempt = $attempts->first();
        $lastAttempt = $attempts->last();
        $invoice = Invoice::where('order_id', $this->id)->first();
        $canAddItem = $this->canAddItem();
        $invoiceLink = setting('app_debug') == 'live' ? 'https://ollops.com/order-pay/' . $this->token  : 'https://beta-app.ollops.com/order-pay/' . $this->token ;
        $max_discount = $this->dropshipper->max_discount;

        return [
            'id' => $this->id,
            'checkOutId' => json_decode($this->checkOutId, true),
            'paymentMethod' => $this->paymentMethod ?? "",
            'invoice' => $invoiceLink ?? "",
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
            'dropshipperName' => $this->dropshipper->email ?? "",
            'status' => new StatusResource($this->status),
            'customerName' => $this->customerName ?? "",
            'customerPhone' => $this->customerPhone ?? "",
            'phoneCode' => $this->phone_code ?? "",
            'customerAddress' => $this->customerAddress ?? "",
            'customerLocation' => $this->customerLocation ?? "",
            'country' => new CountryListResource($this->country),
            'deliveryDate' => $this->deliveryDate,
            'cancelDate' => $this->cancelDate,
            'orderDate' => $this->created_at->format('Y-m-d H:i'),
            'customerCity' => new CityResource($this->city),
            'weight' => $this->weight ?? 0,
            'tracking_number' => $this->tracking_number ?? '',
            'pdf_label' => $this->pdf_label ?? '',
            'wasRefunded' => $this->wasRefunded(),
            'district' => $this->district,
            'confirmed' => $this->getConfirmedOrder($this->status_id),
            'first_attempt' => $firstAttempt ? $firstAttempt->created_at->format('Y-m-d H:i') : null,
            'last_attempt' => $lastAttempt ? $lastAttempt->created_at->format('Y-m-d H:i') : '-',
            'number_of_attempts' => $this->attempts_count,
            'ollops_attempts' => $this->ollops_attempts,
            'notes' => $this->notes,
            'nextPossibleStatuses' => $this->getNextStatuses($this),
            'sub_status_id' => $this->sub_status_id,
            'sub_status' => $this->subStatus,
            'remark_id' => $this->remark_id,
            'subStatuses' => $this->getSubStatuses($this),
            'remarks' => $this->getRemarks($this),
            'updated_at' => $this->updated_at->format('Y-m-d H:i'),
            'validated' => $this->validated ? \Carbon\Carbon::parse($this->validated)->format('Y-m-d H:i') : null,
            'validated_by' => $this->validated_by,
            'validated_indicator' => $this->validated ? true : false,
            'duplicated_order_ids' => $this->duplicated_order_ids,
            'is_duplicated' => $this->is_duplicated,
            'operator' => $this->operator,
            'assigned_at' => $this->assigned_at,
            'sent_to_ollops_at' => $this->sent_to_ollops_at,
            'ollops_token' => $this->ollops_token,
            'ollops_order_id' => $this->ollops_order_id,
            'ollops_confirmation_status' => $this->ollops_confirmation_status,
            'delivery_duration' => $this->delivery_duration(),
            'canAddItem' => $canAddItem,
            'addedPercentage' => $canAddItem ? $this->addedPercentage() : null,
            'userCanUpdateOrder' => $this->userCanUpdateOrder(),
            'vat' => $this->getOrderVat(),
            'source_platform'=>$this->source_platform,
            'created_platform'=>$this->created_platform,
            'allow_discounts' => $this->allow_discounts,
            'max_discount' => $max_discount,
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

    private function userCanUpdateOrder()
    {
        $canUpdate = true;
        if(! auth()->check()) {
            return true;
        }
        if(auth()->user()->can('view_all_order') && auth()->user()->can('update_order')) {
            return true;
        }

        if(! auth()->user()->can('update_order')) {
            $canUpdate = false;
        }
        if($this->operator_id && $this->operator_id != auth()->id()) {
            $canUpdate = false;
        }

        return $canUpdate;
    }

    // statuses
    private function getNextStatuses($order)
    {
        $nextStatuses = $this->nextPossibleStatuses();

        $statusOptions = [];
        $statusOptions[] = [
            'id' => $order->status->id,
            'name' => $this->getModifiedName($order->status->name->value),
            'selected' => true
        ];

        foreach ($nextStatuses as $nextStatus) {
            $statusOptions[] = [
                'id' => $nextStatus->id,
                'name' => $this->getModifiedName($nextStatus->name->value),
                'selected' => false
            ];
        }

        return $statusOptions;
    }

    private function getModifiedName($name)
    {
        switch ($name) {
            case 'new':
                return 'New';
            case 'pending':
                return 'Pending confirmation';
            case 'shipping':
                return 'Shipping';
            case 'rejected':
                return 'Rejected';
            case 'completed':
                return 'Completed';
            case 'canceled':
                return 'Canceled';
            case 'pay_pending':
                return 'Pending payment';
            case 'payPending':
                return 'Pending payment';
            case 'preparing':
                return 'Preparing';
            case 'pending_inventory':
                return 'Pending inventory';
            case 'return_balance':
                return 'RETURN BALANCE';
            default:
                return $name;
        }
    }

    // Substatuses
    private function getSubStatuses($order)
    {
        $subStatusOptions = [];

        $subStatusOptions[] = [
            'id' => '',
            'name' => 'Select Sub Status',
            'selected' => $order->subStatus ? false : true
        ];
        // Add current sub-status to subStatusOptions array if it exists
        if ($order->subStatus) {
            $subStatusOptions[] = [
                'id' => $order->subStatus->id,
                'name' => $order->subStatus->name,
                'selected' => true
            ];
        }

        $statusSubStatuses = $order->status->subStatuses()->where('id', '!=', $order->subStatus?->id)->select('id', 'name')->get();
        foreach ($statusSubStatuses as $subStatus) {
            $subStatusOptions[] = [
                'id' => $subStatus->id,
                'name' => $subStatus->name,
                'selected' => false
            ];
        }

        return $subStatusOptions;
    }

    private function getRemarks($order)
    {
        $remarkOptions = [];

        $remarkOptions[] = [
            'id' => '',
            'name' => 'Select Remark',
            'selected' => $order->remark ? false : true
        ];

        if ($order->remark) {
            $remarkOptions[] = [
                'id' => $order->remark->id,
                'name' => $order->remark->name,
                'selected' => true
            ];
        }

        if ($order->subStatus) {
            $subStatusRemarks = $order->subStatus->remarks()->where('id', '!=', $order->remark?->id)->select('id', 'name')->get();
            foreach ($subStatusRemarks as $remark) {
                $remarkOptions[] = [
                    'id' => $remark->id,
                    'name' => $remark->name,
                    'selected' => false
                ];
            }
        }

        return $remarkOptions;
    }

    private function getConfirmedOrder($status)
    {
        $unconfirmedStatuses = [
            OrderEnum::NEW_STATUS,
            OrderEnum::PENDING_STATUS,
            OrderEnum::PAY_PENDING_STATUS,
            OrderEnum::ONHOLD_STATUS,
        ];

        return !in_array($status, $unconfirmedStatuses);
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
        if ($this->status_id !== Order::COMPLETED_STATUS) return false;
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
        if (OrderRefund::where('order_id', $this->id)->exists()) {
            return true;
        }
        return false;
    }

    private function canAddItem()
    {
        if( $this->status_id === OrderEnum::NEW_STATUS || $this->status_id === OrderEnum::PENDING_STATUS || $this->status_id === OrderEnum::ONHOLD_STATUS)
        {
            if($this->dropshipper->extra_product_feature_enabled) return true;
        }

        return false;
    }

    private function addedPercentage()
    {
        return $this->dropshipper->product_price_percentage / 100;
    }
}
