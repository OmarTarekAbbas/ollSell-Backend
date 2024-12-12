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

class ExportValidationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;
    protected $filters;

    public function __construct($user, array $filters)
    {
        $this->user = $user;
        $this->filters = $filters;
    }

    public function handle()
    {
        $request = new Request($this->filters);

        auth()->setUser(User::first());

        $cacheKey = 'export_validation-' . $this->user->id;

        try {
            $timestamp = Carbon::now()->timestamp;
            $filePath = 'exports/validation--' . $timestamp . '.csv';
            $fileHandle = fopen(storage_path('app/public/' . $filePath), 'w');

            // Write the CSV header with UTF-8 BOM for correct encoding
            fprintf($fileHandle, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($fileHandle, $this->getCsvHeaders());

            $perPage = 10;
            $page = 1;

            do {
                $request->merge(['page' => $page, 'per_page' => $perPage]);
                $paginatedOrders = $this->queryCode($request, true, $perPage);
                $orders = $paginatedOrders['data'];

                foreach ($orders as $order) {
                    $order = Order::find($order['id']);
                    $this->writeOrderToCsv($fileHandle, $order);
                }

                $page++;
            } while (count($orders) === $perPage);

            fclose($fileHandle);

            // Send an email with the file attached
            $this->sendEmailWithAttachment($filePath);
        } catch (\Exception $e) {
            Log::error('Error exporting orders: ' . $e->getMessage());
        } finally {
            cache()->forget($cacheKey);
        }
    }

    protected function getCsvHeaders(): array
    {
        return [
            'Order ID',
            'Created Date',
            'Validated Date',
            'Validated By',
        ];
    }

    protected function writeOrderToCsv($fileHandle, $order)
    {
        // Map all fields correctly and handle Arabic text and phone number formatting
        fputcsv($fileHandle, [
            $order->id,
            $order->created_at,
            $order->validated ? "\t" . $order->validated : '-',
            $this->getValidatedBy($order),
        ]);
    }

    private function getValidatedBy($order)
    {
        if ($order->validated_by === 'system') {
            return 'System - ' . $order->validationOperator?->name;
        }

        return $order->validated_by;
    }

    protected function sendEmailWithAttachment($filePath)
    {
        $fileUrl = Storage::disk('public')->url($filePath);
        Mail::to($this->user->email)->send(new OrdersExported($fileUrl, $filePath));
    }

    protected function queryCode($request)
    {
        $query = Order::query();

        $query->whereNotNull('validated');

        if ($request->validated_by) {
            if ($request->validated_by === 'system') {
                // Include both 'system' and null values for system orders
                $query->where(function ($query) {
                    $query->where('validated_by', 'system')
                        ->orWhereNull('validated_by');
                });
            } else {
                $query->where('validated_by', $request->validated_by);
            }
        }

        // Validated at filter
        if ($request->validated_from && $request->validated_to) {
            $query->whereBetween('validated', [
                Carbon::parse($request->validated_from)->startOfDay(),
                Carbon::parse($request->validated_to)->endOfDay()
            ]);
        }

        if ($request->validated_from) {
            $query->where('validated', '>=', Carbon::parse($request->validated_from)->startOfDay());
        }
        if ($request->validated_to) {
            $query->where('validated', '<=', Carbon::parse($request->validated_to)->endOfDay());
        }

        // Created at filter
        if ($request->created_from && $request->created_to) {
            $query->whereBetween('created_at', [
                Carbon::parse($request->created_from)->startOfDay(),
                Carbon::parse($request->created_to)->endOfDay()
            ]);
        }

        if ($request->created_from) {
            $query->where('created_at', '>=', Carbon::parse($request->created_from)->startOfDay());
        }
        if ($request->created_to) {
            $query->where('created_at', '<=', Carbon::parse($request->created_to)->endOfDay());
        }

        // Handle pagination parameters
        $itemsPerPage = $request->get('itemsPerPage', 10);
        $orders = $query->orderBy('created_at', 'desc')->paginate($itemsPerPage);

        return collect($orders);
    }
}
