<?php

namespace Modules\Order\Exports\Order\Admin;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Modules\Order\Entities\Order;
use Modules\Order\Enums\ClickPayEnum;
use Modules\Order\Enums\OrderEnum;

class OrderExport implements FromCollection, WithHeadings
{
    protected $orders;
    protected $title;

    public function __construct($orders)
    {
        $this->orders = $orders;
    }

    public function collection()
    {
        $exportData = [];
        foreach($this->orders as $order)
        {
            $isFirstOrderItem = true;
            $order = Order::find($order['id']);
            $attempts = $order->followUps()->where('activity_type', '!=', 'Initiated')->get();
            $firstAttempt = $attempts->first();
            $lastAttempt = $attempts->last();
            foreach($order->orderItems as $orderItem)
            {
                App::setlocale('ar');
                $productObject = json_decode($orderItem->product_json);
                if($productObject)
                {
                    $name = $productObject->product_name;
                    $sku = $productObject->sku;
                }else
                {
                    $name = $orderItem->product ? $orderItem->product->name->value : '-';
                    $sku = $orderItem->product ? $orderItem->product->sku : '-';
                }
                $notesAdmin = [];
                $notesClient = [];
                $notesAdminUser = [];
                foreach($order->notes as $note)
                {
                    if(!$note) continue;
                    if($note->user)
                    {
                        $notesAdmin[] = $note->content ?? "";
                        $notesAdminUser[] = $note->user->name;
                    }else
                    {
                        $notesClient[] = $note->content ?? "";
                    }
                }
                App::setlocale('en');
                if($order->status_id == OrderEnum::PENDING_STATUS && !is_null($order->validated) && is_null($order->tracking_number))
                {
                    $status = 'Validated';
                }else
                {
                    $status = $order->status->name->value;
                }
                // Gather data for each order item
                $exportData[] = [
                    'Order ID' => $order->id,
                    'Date' => $order->created_at,
                    'Status' => $status,
                    'Sub Status' => $order->subStatus?->name ?? '-',
                    'Remark' => $order->remark?->name ?? '-',
                    'Validated' => $order->validated ?? '-',
                    'Dropshipper ID' => $order->dropshipper_id,
                    'Dropshipper Name' => $order->dropshipper->first_name . ' ' . $order->dropshipper->last_name,
                    'Recipient Name' => $order->customerName,
                    'Recipient Phone' => $order->customerPhone,
                    'Address' => $order->customerAddress,
                    'District' => $order->district,
                    'City' => $order->city->name->value,
                    'Country' => $order->country->name->value,
                    'Location' => $order->customerLocation,
                    'Payment Method' => $this->getPaymentMethod($order->paymentMethod),
                    'Supplier ID' => $orderItem->product?->supplier_id,
                    'Product Name' => $name,
                    'SKU' => $sku,
                    'Quantity' => $orderItem->quantity,
                    'Unit Price' => number_format($orderItem->unitPrice, 2, '.', ''),
                    'Supplier Price' => number_format($orderItem->product?->supplier_price_cost, 2, '.', ''),
                    'Total Price' => number_format($orderItem->totalPrice, 2, '.', ''),
                    'SubTotal' => $isFirstOrderItem ? number_format($order->subTotal, 2, '.', '') : '',
                    'VAT' => $isFirstOrderItem ? number_format($order->totalVat, 2, '.', '') : '',
                    'Grand Total' => $isFirstOrderItem ? number_format($order->grandTotal, 2, '.', '') : '',
                    'Tracking number' => $order->tracking_number,
                    'Shipping Url' => $order->pdf_label,
                    'First attempt' => $firstAttempt ? $firstAttempt->created_at->format('Y-m-d H:i') : null,
                    'Last attempt' => $lastAttempt ? $lastAttempt->created_at->format('Y-m-d H:i') : '-',
                    'Attempts' => $order->attempts_count,
                    'Ollops Attempts' => $order->ollops_attempts,
                    'operator' => $order->operator ? $order->operator->name : null,
                    'assigned at' => $order->assigned_at ? $order->assigned_at : null,
                    'validation by' => $order->validationOperator ? $order->validationOperator->name : (in_array($order->ollops_confirmation_status,
                        ['confirmed', 'cancelled']) ? 'ollops' : "system"),
                    'Net Profit' => $order->net_profit,
                    'source platform' => $order->source_platform,
                    'created platform' => $order->created_platform,
                    'note' => implode(",", $notesAdmin),
                    'note Creator' => implode(",", $notesAdminUser),
                    'note client' => implode(",", $notesClient)
                ];
                $isFirstOrderItem = false;
            }
        }
        return collect($exportData);
    }

    public function headings(): array
    {
        return [
            'Order ID',
            'Date',
            'Status',
            'Sub Status',
            'Remark',
            'Validated',
            'Dropshipper ID',
            'Dropshipper Name',
            'Recipient Name',
            'Recipient Phone',
            'Address',
            'District',
            'City',
            'Country',
            'Location',
            'Payment Method',
            'Supplier ID',
            'Product Name',
            'SKU',
            'Quantity',
            'Unit Price',
            'Supplier Price',
            'Total Price',
            'SubTotal',
            'VAT',
            'Grand Total',
            'Tracking number',
            'Shipping Url',
            'First attempt',
            'Last attempt',
            'Attempts',
            'Ollops Attempts',
            'Operator',
            'assigned at',
            'validation by',
            'Net Profit',
            'source platform',
            'created platform',
            'Note',
            'Note Creator',
            'Note Client'
        ];
    }

    private function getPaymentMethod($paymentMethod)
    {
        if($paymentMethod == 1) return 'Online';
        if($paymentMethod == 2) return 'Cash on delivery';
        if($paymentMethod == 3) return 'Wallet';
    }
}
