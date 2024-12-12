<?php

namespace Modules\Report\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Modules\Order\Entities\Order;

class ValidationOperatorExport implements FromCollection, ShouldAutoSize, WithHeadings
{


    public function collection()
    {
        $request = request();
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
        $orders = $query->orderBy('created_at', 'desc')->get();

        // Map orders to match the headings
        return $orders->map(function ($order) {
            return [
                'Order ID'       => $order->id,
                'Created Date'   => $this->formatDate($order->created_at),
                'Validated Date' => $this->formatDate($order->validated),
                'Validated By'   => $this->getValidatedBy($order),
            ];
        });
    }

    private function formatDate($date)
    {
        // Check if the date is a string or an instance of Carbon
        if ($date instanceof Carbon) {
            return $date->format('Y-m-d H:i:s');
        } elseif (is_string($date)) {
            return Carbon::parse($date)->format('Y-m-d H:i:s');
        }

        // Return null if the date is invalid
        return null;
    }

    private function getValidatedBy($order)
    {
        if ($order->validated_by === 'system' || $order->validationOperator !== null) {
            return 'System - ' . $order->validationOperator?->name;
        }

        return $order->validated_by;
    }

    public function headings(): array
    {
        return [
            'Order ID',
            'Created Date',
            'Validated Date',
            'Validated By',
        ];
    }
}
