<?php

namespace Modules\Order\Imports;

use Modules\Order\Entities\Order;
use Illuminate\Support\Collection;
use Modules\Order\Entities\Remark;
use Modules\Order\Enums\OrderEnum;
use Illuminate\Support\Facades\Log;
use Modules\Order\Entities\FollowUp;
use Modules\Order\Entities\SubStatus;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;

class UpdateOrdersImport implements ToCollection, WithStartRow
{
    public function __construct(){}

    /**
     * The function processes a collection of rows, validates the data, and stores the valid rows as
     * orders while storing the invalid rows as a report.
     *
     * param Collection rows A collection of rows from an Excel file that contains order data.
     */
    public function collection(Collection $rows)
    {
        foreach($rows as $row)
        {
            $order = Order::find((int)$row[4]);
            if(!$order) continue;
            $order->notes()->delete();
            if($row[16])
            {
                $order->update([
                    'attempts_count' => $row[16],
                ]);
            }
            // save validated at
            if($row[20])
            {
                $timestamp = ($row[20] - 25569) * 86400; // Convert Excel date to Unix timestamp
                // Format timestamp into a readable date format
                $validated = date('Y-m-d H:i:s', $timestamp);
                $order->update([
                    'validated' => $validated,
                ]);
            }else
            {
                $order->update([
                    'validated' => null,
                ]);
            }
            // save validation note
            if($row[24] && $row[24] != '-')
            {
                $order->notes()->create([
                    'content' => $row[24]
                ]);
            }
            // update first and last attempt
            if($row[19])
            {
                $firstAttempt = FollowUp::where('order_id', $order->id)
                    ->where('activity_type', '!=', 'Initiated')->first();
                if($firstAttempt)
                {
                    $timestamp = ($row[19] - 25569) * 86400; // Convert Excel date to Unix timestamp
                    // Format timestamp into a readable date format
                    $firstAttemptDate = date('Y-m-d H:i:s', $timestamp);
                    $firstAttempt->update([
                        'created_at' => $firstAttemptDate
                    ]);
                }
            }
            // if order passed validation
            // else update status and remarks
            if($row[25] || $row[26])
            {
                $order->update([
                    'tracking_number' => $row[25],
                    'pdf_label' => $row[26],
                ]);
                // check status
                if($row[27] == 'Delivered')
                {
                    $order->update([
                        'status_id' => OrderEnum::COMPLETED_STATUS,
                        'sub_status_id' => null,
                    ]);
                }else if($row[27] == 'Returned' || $row[27] == 'Cancelled' || $row[28] == 'Rejected')
                {
                    $order->update([
                        'status_id' => OrderEnum::REJECTED_STATUS,
                        'sub_status_id' => null,
                    ]);
                }else if($row[27] == 'AWB created at origin')
                {
                    $order->update([
                        'status_id' => OrderEnum::PREPARING_STATUS,
                        'sub_status_id' => null,
                    ]);
                }else
                {
                    $order->update([
                        'status_id' => OrderEnum::SHIPPING_STATUS,
                        'sub_status_id' => null,
                    ]);
                }
                if($row[29] && $row[29] != '-')
                {
                    $order->notes()->create([
                        'content' => $row[29]
                    ]);
                }
            }else
            {
                if($row[17] == 'New')
                {
                    $order->update(['status_id' => OrderEnum::PENDING_STATUS]);
                }else
                {
                    if($row[18])
                    {
                        $subStatus = SubStatus::where('name', $row[17])
                            ->whereHas('remarks', function($query) use ($row)
                            {
                                $query->where('name', $row[18]);
                            })
                            ->first();
                        if(!$subStatus && $row[17] == 'Validated')
                        {
                            $subStatus = SubStatus::where('name', $row[17])
                                ->where('status_id', OrderEnum::PENDING_STATUS)
                                ->first();
                        }else if(!$subStatus)
                        {
                            $subStatus = SubStatus::where('name', $row[17])->first();
                        }
                    }else if($row[17] == 'Validated')
                    {
                        $subStatus = SubStatus::where('name', $row[17])
                            ->where('status_id', OrderEnum::PENDING_STATUS)
                            ->first();
                    }else
                    {
                        $subStatus = SubStatus::where('name', $row[17])->first();
                    }
                    if($subStatus)
                    {
                        $order->update(['status_id' => $subStatus->status_id]);
                        $order->update([
                            'sub_status_id' => $subStatus->id
                        ]);
                        if($row[18])
                        {
                            $remark = Remark::where('name', $row[18])->first();
                            if($remark)
                            {
                                $order->update([
                                    'remark_id' => $remark->id
                                ]);
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * The function checks if an array contains only null values.
     *
     * param input The input parameter is a variable that contains an array of values to be checked
     * for containing only null values.
     *
     * return bool The function `containsOnlyNull` is returning a boolean value. It will return `true`
     * if the input array contains only `null` values, and `false` otherwise.
     */
    function containsOnlyNull($input): bool
    {
        return empty(array_filter($input, function($a)
        {
            return $a !== null;
        }));
    }

    /**
     * return int
     */
    public function startRow(): int
    {
        return 3;
    }
}
