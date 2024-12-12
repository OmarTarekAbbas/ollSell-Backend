<?php

namespace Modules\Order\Actions\Order;

use Carbon\Carbon;
use Modules\Order\Enums\OrderEnum;
use Modules\Order\Entities\OllopsInteractionLog;
use Modules\Order\Actions\Order\CreateShipmentOrderAction;

class SendOrderToOllopsAction
{
    protected $order;

    protected $request;

    public function __construct($order, $request)
    {
        $this->order = $order;
        $this->request = $request;
    }

    public function execute()
    {
        // Update order status and address based on the confirmation status
        if ($this->request->status == 'confirmed' || $this->request->status == 'order_confirmed') {
            $this->handleConfirmedOrder();
        } else if($this->request->status == 'cancelled') {
            $this->handleCancelOrder();
        }else {
            $this->handleNotValidatedOrder();
        }
        // Save the changes to the order
        $this->order->save();
    }

    protected function handleConfirmedOrder()
    {
        // Update order status and validation timestamp
        if ($this->order->status_id == OrderEnum::PENDING_STATUS || $this->order->status_id == OrderEnum::NEW_STATUS) {
            $this->order->validated_by = $this->request->validated_by;
            // App(CreateShipmentOrderAction::class)->execute($this->order);
            $this->order->validated = now();
        }
        $this->order->ollops_confirmation_status = 'confirmed';
        if ($this->request->address) {
            // Update order address if it has changed
            $this->updateOrderAddressIfChanged();
        }
        // Update the message times
        $this->order->first_message_time = $this->request->first_message_time
            ? Carbon::parse($this->request->first_message_time)->format('Y-m-d H:i:s')
            : $this->order->first_message_time;
        $this->order->second_message_time = $this->request->second_message_time
            ? Carbon::parse($this->request->second_message_time)->format('Y-m-d H:i:s')
            : $this->order->second_message_time;
        $this->order->third_message_time = $this->request->third_message_time
            ? Carbon::parse($this->request->third_message_time)->format('Y-m-d H:i:s')
            : $this->order->third_message_time;
        // Log the order update interaction
        $this->logInteraction(OllopsInteractionLog::ORDER_CONFIRMED, [
            'status' => OllopsInteractionLog::ORDER_CONFIRMED,
            'address' => [
                'city' => $this->order->customerCity,
                'district' => $this->order->customerAddress,
                'customerAddress' => $this->order->district,
            ],
        ]);
    }

    protected function handleCancelOrder()
    {
        // Update order status to not validated
        if ($this->order->status_id == OrderEnum::PENDING_STATUS || $this->order->status_id == OrderEnum::NEW_STATUS) {
            $this->order->status_id = 5;
            $this->order->sub_status_id = 9;
            $this->order->cancelDate = Carbon::now();
        }

        $this->order->ollops_confirmation_status = 'cancelled';

        // Log the order rejection interaction
        $this->logInteraction(OllopsInteractionLog::ORDER_CANCELLED, [
            'status' => OllopsInteractionLog::ORDER_CANCELLED,
        ]);
    }

    protected function handleNotValidatedOrder()
    {
        // Update order status to not validated
        if($this->order->status_id == OrderEnum::PENDING_STATUS)
        {
            $this->order->sub_status_id = 7;
        } elseif ($this->order->status_id == OrderEnum::NEW_STATUS) {
            $this->order->status_id = OrderEnum::PENDING_STATUS;
            $this->order->sub_status_id = 7;
        }
        $this->order->ollops_confirmation_status = 'not_validated';
        // Log the order rejection interaction
        $this->logInteraction(OllopsInteractionLog::ORDER_REJECTED, [
            'status' => OllopsInteractionLog::ORDER_REJECTED,
        ]);
    }

    protected function updateOrderAddressIfChanged()
    {
        // Extract the new address details from the request
        $newCity = $this->request->address['city'] ?? null;
        $newDistrict = $this->request->address['district'] ?? null;
        $newCustomerAddress = $this->request->address['customerAddress'] ?? null;

        // Check if any part of the address has changed
        $addressChanged = $this->hasAddressChanged($newCity, $newDistrict, $newCustomerAddress);

        if ($addressChanged) {
            // Update the order's address details
            $this->order->customerCity = $newCity;
            $this->order->district = $newDistrict;
            $this->order->customerAddress = $newCustomerAddress;

            // Update the order's confirmation status
            $this->order->ollops_confirmation_status = OllopsInteractionLog::ORDER_CONFIRMED;

            // Save the changes to the order
            $this->order->save();
        }
    }

    /**
     * Check if the address has changed
     *
     * @param string|null $newCity
     * @param string|null $newDistrict
     * @param string|null $newCustomerAddress
     * @return bool
     */
    protected function hasAddressChanged($newCity, $newDistrict, $newCustomerAddress)
    {
        return $this->order->customerCity !== $newCity ||
            $this->order->district !== $newDistrict ||
            $this->order->customerAddress !== $newCustomerAddress;
    }


    protected function logInteraction($action, array $details)
    {
        // Log the order interaction
        $interactionLog = new OllopsInteractionLog;
        $interactionLog->order_id = $this->order->id;
        $interactionLog->action = $action;
        $interactionLog->details = json_encode($details);
        $interactionLog->save();
    }
}
