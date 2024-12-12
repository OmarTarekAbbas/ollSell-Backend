<?php

namespace Modules\Order\Exports\Order;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Modules\Order\Service\OrderService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class MissingOrdersExport implements FromCollection, WithHeadings, WithStartRow, WithColumnFormatting
{
    /* `public ;` is declaring a public property called `` for the class. This property can
    be accessed and modified from outside the class. In this specific class, `` is being used
    to store an array of data that will be used to generate an Excel file. */
    public $data;

    /**
     * This PHP function returns an array of headings for a table with two columns: "Notes" and
     * "Errors".
     * 
     * return array An array containing two string values: 'Notes' and 'Errors'.
     */
    public function headings(): array
    {
        return [
            'اسم العميل',
            'رقم الهاتف',
            'العنوان',
            'الحي',
            'المدينة',
            'الدولة',
            'الموقع',
            'وسيلة الدفع',
            'كود المنتج SKU',
            'عدد القطع',
            'سعر البيع للوحدة',
            'رسالة الخطأ',
        ];
    }

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
     * This function converts an array of data into a collection with specific keys and returns it.
     * 
     * return Collection A collection of data with each row containing information about a
     * promotion/advertisement by an influencer, including details such as the date, influencer name,
     * company, platform, promoted products, categories, target market, ad type, promotion type, agent,
     * whether the ad word was mentioned, discount, notes, and errors (if any).
     */
    public function collection(): Collection
    {

        $data = [];
        foreach ($this->data as $datum) {
            $data[] = [
                'name' => $datum[0] ?? null,
                "phone" => "\t" . $datum[1] ?? null,
                'address' => $datum[2] ?? null,
                'district' => $datum[3] ?? null,
                'city' => $datum[4] ?? null,
                'country' => $datum[5] ?? null,
                'source_platform' => $datum[6] ?? null,
                'paymentMethod' => $datum[7] ?? null,
                'sku' => $datum[8] ?? null,
                'totalQuantity' => $datum[9] ?? null,
                'price' => $datum[10] ?? null,
                'message' => $datum[11] ?? $datum['message'],
                'id' => $datum[12] ?? null,
            ];
        }
        return collect($data);
    }

    /**
     * The function "startRow" returns the integer value 1.
     * 
     * return int an integer value of 1.
     */
    public function startRow(): int
    {
        return 1;
    }

    public function columnFormats(): array
    {
        return [
            // 'B' => DataType::TYPE_STRING,
            // 'I' => DataType::TYPE_STRING,
            'I' => NumberFormat::FORMAT_GENERAL,
        ];
    }
}
