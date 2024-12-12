<?php

namespace Modules\Order\Actions\Order;

use Illuminate\Support\Carbon;
use Modules\Order\Entities\Order;
use Modules\Order\Enums\ClickPayEnum;
use Modules\Order\Enums\OrderEnum;
use Modules\Order\Enums\PaymentEnum;
use Modules\Order\Service\OrderItemService;

class SyncUpdateOrderStatusAction
{
    protected $order;
    protected $status_id;

    public function __construct($order, $status_id)
    {
        $this->order = $order;
        $this->status_id = $status_id;
    }

    public function execute()
    {
        // completed status
        if($this->order->status_id !== $this->status_id && $this->status_id == OrderEnum::COMPLETED_STATUS)
        {
            $this->order->deliveryDate = now()->format('Y-m-d');
        }
        if($this->order->status_id !== $this->status_id && $this->status_id == OrderEnum::ONHOLD_STATUS)
        {
            $this->order->cancelDate = null;
        }
        // cancelled status
        if($this->order->status_id !== $this->status_id && $this->status_id == OrderEnum::CANCELED_STATUS)
        {
            $this->order->cancelDate = Carbon::now();
            if((int)$this->order->paymentMethod === PaymentEnum::WALLET_METHOD_ID && !in_array($this->order->status_id,
                    [OrderEnum::NEW_STATUS, OrderEnum::PENDING_STATUS, OrderEnum::PAY_PENDING_STATUS, OrderEnum::ONHOLD_STATUS]))
            {
                $walletBalance = $this->getWalletBalance($this->order);

                $this->updateDropshipperWallet($walletBalance);
            }
            if($this->order->status_id != OrderEnum::RETURN_BALANCE_STATUS)
            {
                $this->checkOrderIsPaid($this->order->status_click_payment);
            }
        }
        // rejected orders
        if($this->order->status_id !== $this->status_id && $this->status_id == OrderEnum::REJECTED_STATUS)
        {
            if((int)$this->order->paymentMethod === PaymentEnum::WALLET_METHOD_ID)
            {
                $walletBalance = $this->getWalletBalance($this->order);

                $this->updateDropshipperWallet($walletBalance);
            }

            if($this->order->status_id != OrderEnum::RETURN_BALANCE_STATUS)
            {
                $this->checkOrderIsPaid($this->order->status_click_payment);
            }
        }
        // shipping order
        if($this->order->status_id != $this->status_id && $this->status_id == OrderEnum::PREPARING_STATUS)
        {
            // START SHIPPING
            App(CreateShipmentOrderAction::class)->execute($this->order);
            $this->order->sub_status_id = null;
            $this->order->remark_id = null;
        }else
        {
            $this->order->status_id = $this->status_id;
            $this->order->sub_status_id = null;
            $this->order->remark_id = null;
        }
        // Save changes to the order
        $this->order->save();
    }

    private function checkOrderIsPaid($status_click_payment)
    {
        if($status_click_payment == ClickPayEnum::Pay)
        {
            $this->order->status_id = OrderEnum::RETURN_BALANCE_STATUS;
        }
    }

    private function updateDropshipperWallet($walletBalance)
    {
        $this->order->dropshipper->update([
            'walletBalance' => $walletBalance
        ]);
    }

    private function getWalletBalance($order)
    {
        return $order->dropshipper->walletBalance - $order->grandTotal;
    }
}
