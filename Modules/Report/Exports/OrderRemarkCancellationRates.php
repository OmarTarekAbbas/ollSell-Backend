<?php

namespace Modules\Report\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Modules\Order\Entities\Order;
use Modules\Order\Enums\OrderEnum;
use Modules\Order\Enums\PlatformEnum;
use Carbon\CarbonInterface;
use Modules\Order\Entities\Remark;

class OrderRemarkCancellationRates implements FromCollection, ShouldAutoSize, WithHeadings
{

    /**
     * The function `collection` fetches cancellation rates data for a specific period and organizes it
     * for export.
     * 
     * @return The `collection()` function is returning a collection of data organized for export. It
     * fetches cancellation rates data based on the current and last periods, then organizes this data
     * into an exportable format with the reason for cancellation and the count of cancellations for
     * each reason.
     */
    public function collection()
    {
        $request = request();
        $currentPeriod = $this->getPeriodBestOnPeriodType($request->period, $request);
        $lastPeriod = $this->getPeriodBestOnPeriodType($request->period, $request);
        $request->merge(['created_at' => [$lastPeriod['from'], $currentPeriod['to']]]);

        $remarkCancellationRates = $this->getRemarkCancellationRates($currentPeriod, $lastPeriod);

        $exportData = collect();
        if (isset($remarkCancellationRates['remark']) && is_array($remarkCancellationRates['remark'])) {
            foreach ($remarkCancellationRates['remark'] as $reason => $count) {
                $exportData->push([
                    'Reason' => $reason,
                    'Count' => $count,
                ]);
            }
        } else {
            $exportData->push([
                'Reason' => 'No Data Available',
                'Count' => '0',
            ]);
        }

        return $exportData;
    }

    /**
     * The function `getPeriodBestOnPeriodType` determines the start and end dates of a period based on
     * the specified type or custom dates.
     * 
     * @param type The `getPeriodBestOnPeriodType` function takes two parameters: `` and
     * ``. The `` parameter is used to determine the period type for which the best
     * results are to be fetched. The function then calculates the period based on the provided type
     * and returns an array with '
     * @param request The `getPeriodBestOnPeriodType` function takes two parameters: `` and
     * ``. The function determines the period based on the `` parameter and returns an
     * array with 'from' and 'to' keys representing the start and end of the period.
     * 
     * @return The function `getPeriodBestOnPeriodType` returns an array containing the start and end
     * dates of a specific period based on the provided `` parameter. The start date is
     * represented by the key 'from' and the end date is represented by the key 'to'.
     */
    public function getPeriodBestOnPeriodType($type, $request)
    {
        $from = $request->fromDate ?? Carbon::now()->firstOfMonth()->startOfDay();
        $to = $request->toDate ?? Carbon::now()->lastOfMonth()->endOfDay();
        switch ($type) {
            case 'this_week':
            case 'thisWeek':
                $periodType = ['from' => Carbon::now()->startOfWeek(CarbonInterface::SATURDAY)
                    ->startOfDay(), 'to' => Carbon::now()
                    ->endOfWeek(Carbon::FRIDAY)->endOfDay()];
                break;
            case 'this_month':
            case 'thisMonth':
                $periodType = ['from' => Carbon::now()->firstOfMonth()->startOfDay(), 'to' => Carbon::now()
                    ->lastOfMonth()
                    ->endOfDay()];
                break;
            case 'this_year':
            case 'thisYear':
                $periodType = ['from' => Carbon::now()->firstOfYear()->startOfDay(), 'to' => Carbon::now()->lastOfYear()
                    ->endOfDay()];
                break;
            case 'custom':
            case 'thisCustom':
                $periodType = [
                    'from' => Carbon::parse($from)->startOfDay(),
                    'to' => Carbon::parse($to)->endOfDay() ?? Carbon::now()->endOfDay()
                ];
                break;
            case 'today':
                $periodType = ['from' => Carbon::now()->startOfDay(), 'to' => Carbon::now()->endOfDay()];
                break;
            default:
                $periodType = ['from' => Carbon::now()->firstOfMonth()->startOfDay(), 'to' => Carbon::now()
                    ->lastOfMonth()
                    ->endOfDay()];
                break;
        }
        return $periodType;
    }

    /**
     * This PHP function retrieves the cancellation rates for different remarks within a specified time
     * period.
     * 
     * @param currentPeriod The `getRemarkCancellationRates` function retrieves the cancellation rates
     * for remarks within a specified period. It filters orders that have been canceled and fall within
     * the current period defined by the `` array.
     * @param lastPeriod The `getRemarkCancellationRates` function you provided retrieves the
     * cancellation rates for each remark within a specified period. The `` parameter is
     * used to filter orders created within the current period, while the `` parameter is
     * not used in the function you shared.
     * 
     * @return An array containing the total count of canceled orders for each remark within the
     * specified current period. The array is structured as follows:
     */
    public function getRemarkCancellationRates($currentPeriod, $lastPeriod)
    {
        $request = request();
        $getRemarkCancellationRates = Order::where('status_id', OrderEnum::CANCELED_STATUS)
            ->whereBetween('created_at', [$currentPeriod['from'], $currentPeriod['to']]);

        if ($request->has('source_platform') && !empty($request->source_platform)) {
            $getRemarkCancellationRates = $getRemarkCancellationRates->where('source_platform', $request->source_platform);
        }

        $getRemarkCancellationRates = $getRemarkCancellationRates->get();

        $total = [];

        foreach (Remark::all() as $remark) {
            $orderRemark = $getRemarkCancellationRates->where('remark_id', $remark->id);
            $count = $orderRemark->count();

            if ($count) {
                $total['remark'][$remark->name] = $count;
            }
        }

        return $total;
    }

    /**
     * Returns the headings for the exported data.
     * 
     * @return array
     */
    public function headings(): array
    {
        return [
            'Reason',
            'Count',
        ];
    }
}
