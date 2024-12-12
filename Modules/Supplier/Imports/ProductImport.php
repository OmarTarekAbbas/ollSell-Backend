<?php

namespace Modules\Supplier\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Illuminate\Http\Request;
use Modules\Supplier\Service\ProductService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Modules\MasterCatalog\Service\ImportProductService;
//todo change
class ProductImport implements ToCollection, WithStartRow, WithChunkReading, ShouldQueue 
{
    /**
     * Method model
     *
     * param array $row
     *
     * return void
     */
    public function collection(Collection $rows)
    {
        app()->make(ImportProductService::class)->importV1($rows);
    }

    /**
     * return int
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
