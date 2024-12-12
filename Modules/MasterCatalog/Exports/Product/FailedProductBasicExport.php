<?php

namespace Modules\MasterCatalog\Exports\Product;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithHeadings;

class FailedProductBasicExport implements FromCollection, WithHeadings, WithStartRow
{
    public array $data;

    /**
     * This is a constructor function in PHP that takes an array as a parameter and assigns it to a
     * property called "data".
     *
     * param array data  is an array parameter that is passed to the constructor of a class. The
     * constructor initializes the class properties with the values of the array. This allows the class
     * to be instantiated with pre-defined data.
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * This PHP function returns an array of headings for a product data export.
     *
     * @return array An array of strings representing the headings of a table. The headings are 'Name
     * EN', 'Description EN', "Name AR", 'Description AR', 'SKU', 'Price', 'Quantity', 'Weight', and
     * 'Category_ID'.
     */
    public function headings(): array
    {
        return [
            'Name',
            'Description',
            'Image',
            'Quantity',
            'Weight',
            'Error message',
        ];
    }

    /**
     * This function converts an array of data into a collection with specific keys and values.
     *
     * @return Collection A collection of data with keys 'Name EN', 'Description EN', 'Name AR',
     * 'Description AR', 'SKU', 'Price', 'Quantity', 'Weight', and 'Category_ID'.
     */
    public function collection(): Collection
    {
        $data = [];
        foreach ($this->data as $datum) {
            $data[] = [
                "Name" => $datum[0],
                'Description' => $datum[1],
                'Image' => $datum[2],
                'Quantity' => $datum[4],
                'Weight' => $datum[5],
                'message' => $datum['error_message'],
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
}
