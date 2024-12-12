<?php

namespace Modules\Acl\Exports\Dropshipper;

use Modules\Order\Enums\OrderEnum;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Modules\Acl\Entities\Dropshipper;
use Maatwebsite\Excel\Concerns\WithStyles;
use Modules\Acl\Service\DropshipperService;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class DropshipperExport implements FromCollection, WithHeadings, WithStartRow, WithStyles, WithStrictNullComparison
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function headings(): array
    {
        return [
            'Joining Date',
            'ID',
            'Name',
            'Email',
            'Phone',
            'First Order',
            'Last Order',
            'Total Orders',
            'Delivered Orders',
            'do you have previous experience in dropshipping ?',
            'how many years have you spent in the filed of dropshipping ?',
            'What markets have you worked in before? (Egypt, Saudi Arabia, UAE) ?',
            'What are the most effective marketing channels for you ?',
            'How much do you spend on advertising monthly ?',
            'What are the main types of products that tend to be sold ?'
        ];
    }

    /**
     * This function converts an array of data into a collection with specific keys and returns it.
     *
     * @return Collection A collection of data with each row containing information about a
     * promotion/advertisement by an influencer, including details such as the date, influencer name,
     * company, platform, promoted products, categories, target market, ad type, promotion type, agent,
     * whether the ad word was mentioned, discount, notes, and errors (if any).
     */
    public function collection(): Collection
    {
        // Duplicate the original request
        $originalRequest = Request::capture(); // Capture the original request
        $newRequest = $originalRequest->duplicate();
        // Merge the 'search' parameter into the duplicated request
        $newRequest->merge([
            'status' => isset($this->request['status']) ? $this->request['status'] : null,
            'search' => $this->request['search'],
            'fromDate' => isset($this->request['fromDate']) ? $this->request['fromDate'] : null,
            'toDate' => isset($this->request['toDate']) ? $this->request['toDate'] : null
        ]);
        //todo change
        $dropshippers = app()->make(DropshipperService::class)->export($newRequest);
        $data = [];
        foreach($dropshippers as $record)
        {
            switch($record->is_old_dropshipper)
            {
                case 1:
                    $is_old_dropshipper= true;
                    break;
                case 0:
                    $is_old_dropshipper= false;
                    break;
                default:
                    $is_old_dropshipper= "";
                    break;

            }
            $data[] = [
                'joiningDate' => $record->created_at,
                'id' => $record->id,
                'name' => $record->first_name ? $record->first_name . ' ' . $record->last_name : '-',
                'email' => $record->email,
                'phone' => "\t" . $record->phone,
                'first_order' => $this->getFirstOrderDate($record),
                'last_order' => $this->getLastOrderDate($record),
                'total_orders' => $this->getTotalOrders($record),
                'delivered_orders' => $this->getDeliveredOrders($record),
                'is_old_dropshipper' => $is_old_dropshipper,
                'getYears' => $this->getYears($record),
                'target_dropshipper' => implode(" - ", $record->onboarding_questionnaire_dropshipper_target_markets->pluck('target_market.name.value')->toArray()),
                'soical_dropshipper' => implode(" - ", $record->onboarding_questionnaire_social()->pluck('social')->toArray()),
                'cost_month_dropshipper' => $record->cost_month_dropshipper,
                'What_are_the_main_types_of_products_that_tend_to_be_sold' => implode(" - ", $record->onboarding_questionnaire_dropshipper_onboarding_category->pluck('onboarding_category.name.value')->toArray()),
            ];
        }
        return collect($data);
    }

    /**
     * The function "startRow" returns the integer value 1.
     *
     * @return int an integer value of 1.
     */
    public function startRow(): int
    {
        return 1;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    private function getFirstOrderDate(Dropshipper $dropshipper)
    {
        $firstOrder = $dropshipper->order()->orderBy('created_at', 'asc')->first();
        return optional($firstOrder)->created_at;
    }

    private function getLastOrderDate(Dropshipper $dropshipper)
    {
        $lastOrder = $dropshipper->order()->orderBy('created_at', 'desc')->first();
        return optional($lastOrder)->created_at;
    }

    private function getTotalOrders(Dropshipper $dropshipper)
    {
        return $dropshipper->order()->count() ?? 0;
    }

    private function getDeliveredOrders(Dropshipper $dropshipper)
    {
        return $dropshipper->order()->where('status_id', OrderEnum::COMPLETED_STATUS)->count() ?? 0;
    }

    private function getYears(Dropshipper $dropshipper)
    {
        switch($dropshipper->number_years_dropshipper)
        {
            case 1:
                return 'less than a year';
            case 2:
                return '1-2 years';
            case 3:
                return '2-4 years';
            case 4:
                return '+5 years';
        }
    }
}
