<?php

namespace Modules\Supplier\Imports;

use Illuminate\Support\Collection;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Modules\MasterCatalog\Service\ImportSupplierProductService;
//todo change
class ProductSupplierImport implements ToCollection, WithStartRow, WithChunkReading, ShouldQueue
{
    /**
     * Method model
     *
     * param array $row
     *
     * @return void
     */
    public function collection(Collection $rows)
    {
        app()->make(ImportSupplierProductService::class)->import($rows);
    }

    /**
     * @return int
     */
    public function startRow(): int
    {
        return 2;
    }

    public function chunkSize(): int
    {
        return 100;
    }
}
