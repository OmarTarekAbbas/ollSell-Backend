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
use Modules\Order\Enums\PaymentEnum;

class OrderSourcesReportExport implements FromCollection, ShouldAutoSize, WithHeadings
{

    public function collection()
    {
        $request = request();
        $currentPeriod = $this->getPeriodBestOnPeriodType($request->period, $request);
        $lastPeriod = $this->getPeriodBestOnPeriodType($request->period, $request);

        $request->merge(['created_at' => [$lastPeriod['from'], $currentPeriod['to']]]);

        $orderAllRates = $this->getOrderAllRates($currentPeriod, $lastPeriod);
        $orderConfirmationRates = $this->getOrderConfirmationRates($currentPeriod, $lastPeriod, $orderAllRates['platformCounts']);

        $result = [
            'orderAllRates' => $orderAllRates,
            'deliveryRatesForTotalOrders' => $this->getDeliveryRates(OrderEnum::COMPLETED_STATUS, false, $currentPeriod, $lastPeriod, $orderAllRates['platformCounts']),
            'getOrderCancellationRates' => $this->getgetOrderCancellationRates($currentPeriod, $lastPeriod, $orderAllRates['platformCounts']),
            'orderConfirmationRates' => $orderConfirmationRates,
            'deliveryRatesForConfirmedOrders' => $this->getDeliveryRates(OrderEnum::COMPLETED_STATUS, true, $currentPeriod, $lastPeriod, $orderConfirmationRates['platformCounts']),
        ];

        $exportData = collect([
            [
                'Order All Rates',
                ($result['orderAllRates']['platformCounts']['website'] ?? 0) . " ( " . ($result['orderAllRates']['platformPercentages']['website'] ?? 0) . "% )",
                ($result['orderAllRates']['platformCounts']['tikTok'] ?? 0) . " ( " . ($result['orderAllRates']['platformPercentages']['tikTok'] ?? 0) . "% )",
                ($result['orderAllRates']['platformCounts']['salla'] ?? 0) . " ( " . ($result['orderAllRates']['platformPercentages']['salla'] ?? 0) . "% )",
                ($result['orderAllRates']['platformCounts']['easyOrder'] ?? 0) . " ( " . ($result['orderAllRates']['platformPercentages']['easyOrder'] ?? 0) . "% )",
                ($result['orderAllRates']['platformCounts']['totalCount'] ?? 0),
                ($result['orderAllRates']['platformPercentages']['totalPercentage'] ?? 0) . "%"
            ],
            [
                'Delivery Rates Based on Total Orders',
                ($result['deliveryRatesForTotalOrders']['platformCounts']['website'] ?? 0) . " ( " . ($result['deliveryRatesForTotalOrders']['platformPercentages']['website'] ?? 0) . "% )",
                ($result['deliveryRatesForTotalOrders']['platformCounts']['tikTok'] ?? 0) . " ( " . ($result['deliveryRatesForTotalOrders']['platformPercentages']['tikTok'] ?? 0) . "% )",
                ($result['deliveryRatesForTotalOrders']['platformCounts']['salla'] ?? 0) . " ( " . ($result['deliveryRatesForTotalOrders']['platformPercentages']['salla'] ?? 0) . "% )",
                ($result['deliveryRatesForTotalOrders']['platformCounts']['easyOrder'] ?? 0) . " ( " . ($result['deliveryRatesForTotalOrders']['platformPercentages']['easyOrder'] ?? 0) . "% )",
                ($result['deliveryRatesForTotalOrders']['platformCounts']['totalCount'] ?? 0),
                ($result['deliveryRatesForTotalOrders']['platformPercentages']['totalPercentage'] ?? 0) . "%"
            ],
            [
                'Cancellation Rates',
                ($result['getOrderCancellationRates']['platformCounts']['website'] ?? 0) . " ( " . ($result['getOrderCancellationRates']['platformPercentages']['website'] ?? 0) . "% )",
                ($result['getOrderCancellationRates']['platformCounts']['tikTok'] ?? 0) . " ( " . ($result['getOrderCancellationRates']['platformPercentages']['tikTok'] ?? 0) . "% )",
                ($result['getOrderCancellationRates']['platformCounts']['salla'] ?? 0) . " ( " . ($result['getOrderCancellationRates']['platformPercentages']['salla'] ?? 0) . "% )",
                ($result['getOrderCancellationRates']['platformCounts']['easyOrder'] ?? 0) . " ( " . ($result['getOrderCancellationRates']['platformPercentages']['easyOrder'] ?? 0) . "% )",
                ($result['getOrderCancellationRates']['platformCounts']['totalCount'] ?? 0),
                ($result['getOrderCancellationRates']['platformPercentages']['totalPercentage'] ?? 0) . "%"
            ],

            [
                'Confirmation Rates',
                ($result['orderConfirmationRates']['platformCounts']['website'] ?? 0) . " ( " . ($result['orderConfirmationRates']['platformPercentages']['website'] ?? 0) . "% )",
                ($result['orderConfirmationRates']['platformCounts']['tikTok'] ?? 0) . " ( " . ($result['orderConfirmationRates']['platformPercentages']['tikTok'] ?? 0) . "% )",
                ($result['orderConfirmationRates']['platformCounts']['salla'] ?? 0) . " ( " . ($result['orderConfirmationRates']['platformPercentages']['salla'] ?? 0) . "% )",
                ($result['orderConfirmationRates']['platformCounts']['easyOrder'] ?? 0) . " ( " . ($result['orderConfirmationRates']['platformPercentages']['easyOrder'] ?? 0) . "% )",
                ($result['orderConfirmationRates']['platformCounts']['totalCount'] ?? 0),
                ($result['orderConfirmationRates']['platformPercentages']['totalPercentage'] ?? 0) . "%"
            ],
            [
                'Delivery Rates for Confirmed Orders',
                ($result['deliveryRatesForConfirmedOrders']['platformCounts']['website'] ?? 0) . " ( " . ($result['deliveryRatesForConfirmedOrders']['platformPercentages']['website'] ?? 0) . "% )",
                ($result['deliveryRatesForConfirmedOrders']['platformCounts']['tikTok'] ?? 0) . " ( " . ($result['deliveryRatesForConfirmedOrders']['platformPercentages']['tikTok'] ?? 0) . "% )",
                ($result['deliveryRatesForConfirmedOrders']['platformCounts']['salla'] ?? 0) . " ( " . ($result['deliveryRatesForConfirmedOrders']['platformPercentages']['salla'] ?? 0) . "% )",
                ($result['deliveryRatesForConfirmedOrders']['platformCounts']['easyOrder'] ?? 0) . " ( " . ($result['deliveryRatesForConfirmedOrders']['platformPercentages']['easyOrder'] ?? 0) . "% )",
                ($result['deliveryRatesForConfirmedOrders']['platformCounts']['totalCount'] ?? 0),
                ($result['deliveryRatesForConfirmedOrders']['platformPercentages']['totalPercentage'] ?? 0) . "%"
            ],

        ]);



        return $exportData;
    }

    /**
     * Calculates order confirmation rates for different platforms.
     *
     * @param array $currentPeriod The current period range.
     * @param array $lastPeriod The last period range.
     * @param array $orderAllRates Counts for all platforms in the period.
     * @return array Returns counts and percentages for confirmed orders by platform.
     */
    public function getOrderConfirmationRates($currentPeriod, $lastPeriod, $orderAllRates)
    {
        $orderIsConfirmationCount = Order::where(function ($query) {
            $query->where('validated_by', 'prepaid')->orWhere(function ($query) {
                $query->where('paymentMethod', PaymentEnum::CASH_ON_DELIVERY_ID)
                    ->where('status_id', OrderEnum::COMPLETED_STATUS);
            });
        })->where('status_id', '!=', OrderEnum::CANCELED_STATUS)
            ->whereBetween('created_at', [$currentPeriod['from'], $currentPeriod['to']])
            ->count();

        $platforms = [
            'website' => PlatformEnum::WEBSITE_PLATFORM,
            'tikTok' => PlatformEnum::TiKTOK_PLATFORM,
            'salla' => PlatformEnum::SALLA_PLATFORM,
            'easyOrder' => PlatformEnum::EASYORDER_PLATFORM,
        ];

        $platformCounts = array_map(function ($platform) use ($currentPeriod) {
            return Order::where(function ($query) {
                $query->where('validated_by', 'prepaid')->orWhere(function ($query) {
                    $query->where('paymentMethod', PaymentEnum::CASH_ON_DELIVERY_ID)
                        ->where('status_id', OrderEnum::COMPLETED_STATUS);
                });
            })->where('status_id', '!=', OrderEnum::CANCELED_STATUS)
                ->where('source_platform', $platform)
                ->whereBetween('created_at', [$currentPeriod['from'], $currentPeriod['to']])
                ->count();
        }, $platforms);

        $percentages = array_map(function ($count) use ($orderIsConfirmationCount) {
            return $orderIsConfirmationCount > 0 ? round(($count / $orderIsConfirmationCount) * 100, 2) : 0;
        }, $platformCounts);

        $platformCounts['totalCount'] = array_sum($platformCounts);
        $percentages['totalPercentage'] = $orderAllRates['totalCount'] > 0
            ? round(($platformCounts['totalCount'] / $orderAllRates['totalCount']) * 100, 2)
            : 0;

        return [
            'orderIsConfirmationCount' => $orderIsConfirmationCount,
            'platformCounts' => $platformCounts,
            'platformPercentages' => $percentages,
        ];
    }

    /**
     * Retrieves order counts and percentages for each platform in the given period.
     *
     * @param array $currentPeriod The current period.
     * @param array $lastPeriod The last period (not used).
     * @return array Counts and percentages by platform and total.
     */
    public function getOrderAllRates($currentPeriod, $lastPeriod)
    {
        $orderIsConfirmationCount = Order::whereBetween('created_at', [$currentPeriod['from'], $currentPeriod['to']])->count();

        $platforms = [
            'website' => PlatformEnum::WEBSITE_PLATFORM,
            'tikTok' => PlatformEnum::TiKTOK_PLATFORM,
            'salla' => PlatformEnum::SALLA_PLATFORM,
            'easyOrder' => PlatformEnum::EASYORDER_PLATFORM,
        ];

        $platformCounts = array_map(function ($platform) use ($currentPeriod) {
            return Order::where('source_platform', $platform)
                ->whereBetween('created_at', [$currentPeriod['from'], $currentPeriod['to']])
                ->count();
        }, $platforms);

        $percentages = array_map(function ($count) use ($orderIsConfirmationCount) {
            return $orderIsConfirmationCount > 0 ? round(($count / $orderIsConfirmationCount) * 100, 2) : 0;
        }, $platformCounts);

        $platformCounts['totalCount'] = array_sum($platformCounts);
        $percentages['totalPercentage'] = array_sum($percentages);

        return [
            'orderIsConfirmationCount' => $orderIsConfirmationCount,
            'platformCounts' => $platformCounts,
            'platformPercentages' => $percentages,
        ];
    }

    /**
     * Calculates delivery rates for orders based on status and confirmation.
     *
     * @param string $status Order status to filter.
     * @param bool $confirmedOnly Whether to include only confirmed orders.
     * @return array Contains count and percentage data by platform.
     */
    public function getDeliveryRates($status, $confirmedOnly = false, $currentPeriod, $lastPeriod, $orderConfirmationRates)
    {
        $orderIsConfirmationCount = Order::whereBetween('created_at', [$currentPeriod['from'], $currentPeriod['to']])
            ->where('status_id', $status)
            ->count();

        $platforms = [
            'website' => PlatformEnum::WEBSITE_PLATFORM,
            'tikTok' => PlatformEnum::TiKTOK_PLATFORM,
            'salla' => PlatformEnum::SALLA_PLATFORM,
            'easyOrder' => PlatformEnum::EASYORDER_PLATFORM,
        ];

        $platformCounts = array_map(function ($platform) use ($status, $confirmedOnly, $currentPeriod) {
            $query = Order::where('status_id', $status)
                ->where('source_platform', $platform)
                ->whereBetween('created_at', [$currentPeriod['from'], $currentPeriod['to']]);

            if ($confirmedOnly) {
                $query->whereNotNull('validated');
            }

            return $query->count();
        }, $platforms);

        foreach ($platformCounts as $key => $value) {
            $percentages[$key] = $orderConfirmationRates[$key] > 0 ? round(($value / $orderConfirmationRates[$key]) * 100, 2) : 0;
        }

        $platformCounts['totalCount'] = array_sum($platformCounts);
        $percentages['totalPercentage'] = $orderConfirmationRates['totalCount'] > 0
            ? round(($platformCounts['totalCount'] / $orderConfirmationRates['totalCount']) * 100, 2)
            : 0;

        return [
            'orderIsConfirmationCount' => $orderIsConfirmationCount,
            'platformCounts' => $platformCounts,
            'platformPercentages' => $percentages,
        ];
    }

    /**
     * Retrieves cancellation rates for orders by platform.
     *
     * @return array Contains count and percentage data by platform.
     */
    public function getgetOrderCancellationRates($currentPeriod, $lastPeriod, $orderAllRates)
    {
        $orderIsCancellationRatesCount = Order::where('status_id', OrderEnum::CANCELED_STATUS)
            ->whereBetween('created_at', [$currentPeriod['from'], $currentPeriod['to']])
            ->count();

        $platforms = [
            'website' => PlatformEnum::WEBSITE_PLATFORM,
            'tikTok' => PlatformEnum::TiKTOK_PLATFORM,
            'salla' => PlatformEnum::SALLA_PLATFORM,
            'easyOrder' => PlatformEnum::EASYORDER_PLATFORM,
        ];

        $platformCounts = array_map(function ($platform) use ($currentPeriod) {
            return Order::where('status_id', OrderEnum::CANCELED_STATUS)
                ->where('source_platform', $platform)
                ->whereBetween('created_at', [$currentPeriod['from'], $currentPeriod['to']])
                ->count();
        }, $platforms);

        $percentages = array_map(function ($count) use ($orderIsCancellationRatesCount) {
            return $orderIsCancellationRatesCount > 0 ? round(($count / $orderIsCancellationRatesCount) * 100, 2) : 0;
        }, $platformCounts);

        $platformCounts['totalCount'] = array_sum($platformCounts);
        $percentages['totalPercentage'] = $orderAllRates['totalCount'] > 0
            ? round(($platformCounts['totalCount'] / $orderAllRates['totalCount']) * 100, 2)
            : 0;

        return [
            'orderIsCancellationRatesCount' => $orderIsCancellationRatesCount,
            'platformCounts' => $platformCounts,
            'platformPercentages' => $percentages,
        ];
    }



    /**
     * The headings() function in PHP returns an array of column headings for a data table.
     * 
     * @return array An array of headings is being returned. The headings include 'Rate Type',
     * 'Website', 'TikTok', 'Salla', 'Easy Order', 'Total Count', and 'Total Percentage'.
     */
    public function headings(): array
    {
        return [
            'Rate Type',
            'Website',
            'TikTok',
            'Salla',
            'Easy Order',
            'Total Count',
            'Total Percentage',
        ];
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
}
