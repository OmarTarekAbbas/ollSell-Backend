<?php

namespace Modules\Order\Exports\Order\Admin;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class FullOrderExport implements WithMultipleSheets
{
    protected $orders;
    protected $chunkSize;

    public function __construct($orders, $chunkSize = 100)
    {
        $this->orders = $orders;
        $this->chunkSize = $chunkSize;
    }

    public function sheets(): array
    {
        $sheets = [];
        $chunks = $this->orders->chunk($this->chunkSize);
        foreach ($chunks as $index => $chunk) {
            $sheets[] = new OrderExport($chunk, ($index + 1));
        }
        return $sheets;
    }
}
