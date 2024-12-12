<?php

namespace Modules\Finance\Exports\RedeemRequest;

use Modules\Finance\Service\WithdrawalRequestService;
use Modules\Order\Enums\OrderEnum;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class RedeemRequestExport implements FromCollection, WithHeadings, WithStartRow, WithStyles, WithStrictNullComparison
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Dropshipper',
            'Dropshipper ID',
            'Dropshipper Phone',
            'Pending Amount',
            'Outstanding Amount',
            'Status',
            'Amount',
            'Reason',
            'Order Id',
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
            'search' => $this->request['search'] ?? "",
            'fromDate' => isset($this->request['fromDate']) ? $this->request['fromDate'] : null,
            'toDate' => isset($this->request['toDate']) ? $this->request['toDate'] : null
        ]);
        //todo change
        $dropshippers = app()->make(WithdrawalRequestService::class)->export($newRequest);
        $data = [];
        foreach($dropshippers as $record)
        {
            $data[] = [
                'ID' => $record->id,
                'Dropshipper' => $record->dropshipper->first_name . ' ' . $record->dropshipper->second_name,
                'Dropshipper ID' => $record->dropshipper_id,
                'Dropshipper Phone' => "\t" . $record->dropshipper->phone,
                'Pending Amount' => number_format($record->dropshipper->transaction->where('isStatus', 0)->sum('profitRatio') , 2) ,
                'Outstanding Amount' => number_format($record->dropshipper->profitBalance, 2),
                'Status' => $record->status,
                'Amount' => $record->amount,
                'Reason' => $record->reason ?? '-',
                'order id' => $record->order_id ?? '-',
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
}
