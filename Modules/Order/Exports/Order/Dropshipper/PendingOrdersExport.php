<?php

namespace Modules\Order\Exports\Order\Dropshipper;

use Illuminate\Support\Facades\Auth;
use Modules\Order\Entities\PendingOrder;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class PendingOrdersExport implements FromCollection, WithHeadings, WithMapping
{
    protected $pendingOrders;

    public function __construct($user)
    {
        $this->pendingOrders = PendingOrder::where('dropshipper_id', $user->id)->with('pendingOrderItems')->get();
    }

    public function collection()
    {
        // We need to return a collection of items to export
        $rows = collect();

        foreach ($this->pendingOrders as $order) {
            foreach ($order->pendingOrderItems as $item) {
                // Decode and format the message field
                $decodedMessages = json_decode($order->message, true);
                $formattedMessage = is_array($decodedMessages) ? implode(', ', $decodedMessages) : '';

                // For each order item, we'll create a row that combines order and item details
                $rows->push([
                    'customer_name' => $order->customer_name,
                    'customer_phone' => $order->customer_phone,
                    'customer_address' => $order->customer_address,
                    'district' => $order->district,
                    'city' => $order->customer_city,
                    'country' => $order->customer_country,
                    'source_platform' => $order->source_platform,
                    'payment_method' => $order->payment_method == 2 ? 'COD' : 'Wallet',
                    'sku' => $item->sku,
                    'quantity' => $item->quantity,
                    'selling_price' => $item->selling_price,
                    'message' => $formattedMessage,
                ]);
            }
        }

        return $rows;
    }

    public function headings(): array
    {
        // Define the column headers
        return [
            'Customer Name',
            'Customer Phone',
            'Customer Address',
            'District',
            'City',
            'Country',
            'Source Platform',
            'Payment Method',
            'SKU',
            'Quantity',
            'Selling Price',
            'Message',
        ];
    }

    public function map($row): array
    {
        // Return the array directly as the mapping is handled during the collection build
        return [
            $row['customer_name'],
            $row['customer_phone'],
            $row['customer_address'],
            $row['district'],
            $row['city'],
            $row['country'],
            $row['source_platform'],
            $row['payment_method'],
            $row['sku'],
            $row['quantity'],
            $row['selling_price'],
            $row['message'],
        ];
    }
}
