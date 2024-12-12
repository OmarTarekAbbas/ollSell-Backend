<?php

namespace Modules\Supplier\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Modules\Supplier\Actions\Order\GetExportedSupplerOrdersAction;

class OrderExport implements FromCollection, WithHeadings
{
    public function collection()
    {//todo change
        $orders = App(GetExportedSupplerOrdersAction::class)->execute();
        $exportData = [];

        foreach ($orders['data'] as $order) {
            $isFirstOrderItem = true;
            foreach ($order->orderItems as $orderItem) {

                if ($orderItem->supplier_id != auth()->id()) {
                    continue;
                }

                $productObject = json_decode($orderItem->product_json);

                if ($productObject) {
                    $name = $productObject->product_name;
                    $sku = $productObject->sku;
                } else {
                    $name = $orderItem->product ? $orderItem->product->name->value : '-';
                    $sku = $orderItem->product ? $orderItem->product->sku : '-';
                }

                // Gather data for each order item
                $exportData[] = [
                    'Order ID' => $order->id,
                    'Date' => $order->created_at,
                    'Status' => $order->status->name->value,
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
                    'Product Name' =>  $name,
                    'SKU' => $sku,
                    'Quantity' => $orderItem->quantity,
                    'Unit Price' => number_format($orderItem->unitPrice, 2, '.', ''),
                    'Total Price' => number_format($orderItem->totalPrice, 2, '.', ''),
                    'SubTotal' => $isFirstOrderItem ? number_format($order->subTotal, 2, '.', '') : '',
                    'VAT' => $isFirstOrderItem ? number_format($order->totalVat, 2, '.', '') : '',
                    'Grand Total' => $isFirstOrderItem ? number_format($order->grandTotal, 2, '.', '') : '',
                    'Tracking number' => $order->tracking_number,
                    'Shipping Url' => $order->pdf_label,
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
            'Total Price',
            'SubTotal',
            'VAT',
            'Grand Total',
            'Tracking number',
            'Shipping Url'
        ];
    }

    // public function columnFormats(): array
    // {
    //     return [
    //         // 'B' => DataType::TYPE_STRING,
    //         'I' => DataType::TYPE_STRING,
    //     ];
    // }

    private function getPaymentMethod($paymentMethod)
    {
        if ($paymentMethod == 1) return 'Online';
        if ($paymentMethod == 2) return 'Cash on delivery';
        if ($paymentMethod == 3) return 'Wallet';
    }
}
