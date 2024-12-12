<?php

namespace Modules\Order\Jobs;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Bus\Queueable;
use Modules\Order\Entities\Order;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Modules\Order\Mail\OrdersExported;
use Illuminate\Support\Facades\Storage;
use Modules\Order\Service\OrderService;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ExportOrdersJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;
    protected $filters;
    protected $columns;

    public function __construct($user, array $filters, array $columns)
    {
        $this->user = $user;
        $this->filters = $filters;
        $this->columns = $columns;
    }

    public function handle(OrderService $orderService)
    {
        $request = new Request($this->filters);
        auth()->setUser(User::first());
        $cacheKey = 'order-export-' . $this->user->id;

        try {
            $timestamp = Carbon::now()->timestamp;
            $filePath = 'exports/orders-' . $timestamp . '.csv';
            $fileHandle = fopen(storage_path('app/public/' . $filePath), 'w');

            // Write CSV headers
            $headers = $this->getCsvHeaders();
            fprintf($fileHandle, chr(0xEF) . chr(0xBB) . chr(0xBF)); // Add UTF-8 BOM
            fputcsv($fileHandle, array_values($headers));

            // Paginate and process orders
            $perPage = 10;
            $page = 1;

            do {
                $request->merge(['page' => $page, 'per_page' => $perPage]);
                $paginatedOrders = $orderService->exportEnhancedList($request, true, $perPage);
                $orders = $paginatedOrders['data'];

                foreach ($orders as $orderData) {
                    $order = Order::find($orderData['id']);
                    if ($order) {
                        $this->writeOrderToCsv($fileHandle, $order, $headers);
                    }
                }

                $page++;
            } while (count($orders) === $perPage);

            fclose($fileHandle);

            // Send email with the CSV file attached
            $this->sendEmailWithAttachment($filePath);
        } catch (\Exception $e) {
            Log::error('Error exporting orders: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
        } finally {
            cache()->forget($cacheKey);
        }
    }

    protected function getCsvHeaders(): array
    {
        // Define all possible headers
        $allHeaders = [
            'order_id' => 'Order ID',
            'date' => 'Date',
            'status' => 'Status',
            'sub_status' => 'Sub Status',
            'remark' => 'Remark',
            'validated' => 'Validated',
            'dropshipper_id' => 'Dropshipper ID',
            'dropshipper_name' => 'Dropshipper Name',
            'recipient_name' => 'Recipient Name',
            'recipient_phone' => 'Recipient Phone',
            'address' => 'Address',
            'district' => 'District',
            'city' => 'City',
            'country' => 'Country',
            'location' => 'Location',
            'payment_method' => 'Payment Method',
            'supplier_id' => 'Supplier ID',
            'product_name' => 'Product Name',
            'sku' => 'SKU',
            'quantity' => 'Quantity',
            'unit_price' => 'Unit Price',
            'supplier_price' => 'Supplier Price',
            'total_price' => 'Total Price',
            'sub_total' => 'SubTotal',
            'vat' => 'VAT',
            'grand_total' => 'Grand Total',
            'tracking_number' => 'Tracking number',
            'shipping_url' => 'Shipping Url',
            'first_attempt' => 'First attempt',
            'last_attempt' => 'Last attempt',
            'attempts' => 'Attempts',
            'operator' => 'Operator',
            'validation_by' => 'Validation by',
            'net_profit' => 'Net Profit',
            'source_platform' => 'Source Platform',
            'created_platform' => 'Created Platform',
            'note' => 'Note',
        ];

        // Filter headers based on selected columns
        return array_intersect_key($allHeaders, array_flip($this->columns));
    }

    protected function writeOrderToCsv($fileHandle, $order, $headers)
    {
        $isFirstOrderItem = true;
        $attempts = $order->followUps()->where('activity_type', '!=', 'Initiated')->get();
        $firstAttempt = $attempts->first();
        $lastAttempt = $attempts->last();

        foreach ($order->orderItems as $orderItem) {
            $row = $this->buildOrderRow($order, $orderItem, $firstAttempt, $lastAttempt, $isFirstOrderItem);

            // Filter row data based on selected columns
            $filteredRow = array_intersect_key($row, $headers);
            fputcsv($fileHandle, $filteredRow);

            $isFirstOrderItem = false;
        }
    }

    protected function buildOrderRow($order, $orderItem, $firstAttempt, $lastAttempt, $isFirstOrderItem)
    {
        $productObject = json_decode($orderItem->product_json);

        return [
            'order_id' => $order->id,
            'created_at' => $this->formatDate($order->created_at),
            'status' => $order->status->name->value ?? '-',
            'sub_status' => $order->subStatus?->name ?? '-',
            'remark' => $order->remark?->name ?? '-',
            'validated' => $order->validated ? "\t" . $order->validated : '-',
            'dropshipper_id' => $order->dropshipper_id,
            'dropshipper_name' => $this->formatFullName($order->dropshipper),
            'customer_name' => $order->customerName ?? '-',
            'customer_phone' => $this->formatPhoneNumber($order->customerPhone),
            'customer_address' => $order->customerAddress ?? '-',
            'district' => $order->district ?? '-',
            'city' => $order->city->name->value ?? '-',
            'country' => $order->country->name->value ?? '-',
            'customer_location' => $order->customerLocation ?? '-',
            'payment_method' => $this->getPaymentMethod($order->paymentMethod),
            'supplier_id' => $orderItem->product?->supplier_id ?? '-',
            'product_name' => $productObject->product_name ?? ($orderItem->product->name->value ?? '-'),
            'sku' => $this->formatSKU($productObject->sku ?? $orderItem->product->sku),
            'quantity' => $orderItem->quantity,
            'unit_price' => $this->formatCurrency($orderItem->unitPrice),
            'supplier_price' => $this->formatCurrency($orderItem->product?->supplier_price_cost),
            'total_price' => $this->formatCurrency($orderItem->totalPrice),
            'sub_total' => $isFirstOrderItem ? $this->formatCurrency($order->subTotal) : '',
            'total_vat' => $isFirstOrderItem ? $this->formatCurrency($order->totalVat) : '',
            'grand_total' => $isFirstOrderItem ? $this->formatCurrency($order->grandTotal) : '',
            'tracking_number' => $order->tracking_number ?? '-',
            'pdf_label' => $order->pdf_label ?? '-',
            'first_attempt' => $this->formatDate($firstAttempt?->created_at),
            'last_attempt' => $this->formatDate($lastAttempt?->created_at),
            'attempts_count' => $order->attempts_count ?? 0,
            'operator' => $order->operator?->name ?? '-',
            'validation_operator' => $this->getValidationOperator($order),
            'net_profit' => $this->formatCurrency($order->net_profit),
            'source_platform' => $order->source_platform ?? '-',
            'created_platform' => $order->created_platform ?? '-',
            'last_note' => $this->getLastNoteContent($order),
        ];
    }

    protected function formatDate($date)
    {
        return $date ? "\t" . $date->format('Y-m-d H:i:s') : '-';
    }

    protected function formatPhoneNumber($phone)
    {
        return $phone ? "\t" . $phone : '-';
    }

    protected function formatFullName($dropshipper)
    {
        return $dropshipper ? "{$dropshipper->first_name} {$dropshipper->last_name}" : '-';
    }

    protected function formatSKU($sku)
    {
        return $sku ? "\t" . $sku : '-';
    }

    protected function formatCurrency($value)
    {
        return $value !== null ? number_format($value, 2, '.', '') : '-';
    }

    protected function getValidationOperator($order)
    {
        return $order->validationOperator?->name ??
            (in_array($order->ollops_confirmation_status, ['confirmed', 'cancelled']) ? 'ollops' : 'system');
    }

    protected function getLastNoteContent($order)
    {
        return collect($order->notes)->last()?->content ?? '-';
    }


    protected function sendEmailWithAttachment($filePath)
    {
        $fileUrl = Storage::disk('public')->url($filePath);
        Mail::to($this->user->email)->send(new OrdersExported($fileUrl, $filePath));
    }

    private function getPaymentMethod($paymentMethod)
    {
        if ($paymentMethod == 1) {
            return 'Online';
        } elseif ($paymentMethod == 2) {
            return 'Cash on delivery';
        } elseif ($paymentMethod == 3) {
            return 'Wallet';
        } else {
            return $paymentMethod;
        }
    }
}
