<?php

namespace Modules\Order\Exports\Order;

use Illuminate\Http\Response;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class WalletExport implements FromCollection, WithHeadings
{
    protected Response $data;
    protected $isStatus;

    public function __construct(Response $data,$isStatus)
    {
        $this->data = $data;
        $this->isStatus = $isStatus;
    }

    public function collection()
    {
        $exportData = [];
        $orders = json_decode($this->data->getContent(),true);
        $isFirstOrderItem = true;
        foreach ($orders["data"]["balanceDetailsTables"] as $order) {
            // Gather data for each order item
            $exportData[] = [
                'ID' => $order["id"],
                'Total Balance' => $order["subTotal"],
                'Grand Total' => $order["grandTotal"],
                'Delivery Date' => $order["deliveryDate"],
                'Profit' => $order["Profit"],
                $this->isStatus ?'Outstanding Balance':'Pending Balance' => $isFirstOrderItem ? $this->isStatus?$orders["data"]["profitBalance"]:$orders["data"]["pendingBalance"]:null,
            ];
            $isFirstOrderItem = false;
        }

        return collect($exportData);
    }

    public function headings(): array
    {
        return [
            'ID',
            'Total Balance',
            'Grand Total',
            'Delivery Date',
            'Profit',
            $this->isStatus ?'Outstanding Balance':'Pending Balance'
        ];
    }
}
