<?php

namespace Modules\Order\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Modules\Order\Service\OrderService;
use Modules\CoreData\Service\CityService;
use Modules\CoreData\Service\CountryService;
use Modules\Order\Entities\Order;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Basic\Entities\Translation;
use Modules\CoreData\Entities\City;
use Modules\CoreData\Entities\Country;
use Modules\MasterCatalog\Entities\Product;
use Modules\Order\Entities\FileOrder;
use Modules\Order\Exports\Order\MissingOrdersExport;

class CityImport implements ToCollection, WithStartRow
{


    /**
     * This is a constructor function that initializes three service objects.
     *
     * param CountryService countryService An instance of the CountryService class, which likely
     * provides functionality related to managing countries (e.g. retrieving a list of countries,
     * adding a new country, updating an existing country, etc.).
     * param CityService cityService An instance of the CityService class, which likely provides
     * functionality related to managing cities (e.g. creating, updating, deleting, and retrieving city
     * data).
     * param OrderService service An instance of the OrderService class, which is likely responsible
     * for handling orders in some way.
     */
    public function __construct()
    {
    }

    /**
     * The function processes a collection of rows, validates the data, and stores the valid rows as
     * orders while storing the invalid rows as a report.
     *
     * param Collection rows A collection of rows from an Excel file that contains order data.
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as  $city) {
            $country = Country::find(178); // 2 sa
            if ($country) {
                $data = City::create(['country_id' => $country->id]);
                $name = ['name' => ['ar' => $city[2], 'en' => $city[1]]];

                foreach (language() as $lang) {

                    if (isset($name['name'][$lang->code])) {
                        $data->translation()->create(['key' => 'name', 'value' => $name['name'][$lang->code], 'language_id' => $lang->id]);
                    }
                }
            }
        }
    }

    /**
     * @return int
     */
    public function startRow(): int
    {
        return 2;
    }
}
