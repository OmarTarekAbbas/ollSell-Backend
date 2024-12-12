<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\MasterCatalog\Entities\Attribute;

class AttributeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'name' => ['ar' => "المقاس", 'en' => "Size"],
            ],
            [
                'name' => ['ar' => "اللون", 'en' => "Color"],
            ],
            [
                'name' => ['ar' => "الخامه", 'en' => "Material"],
            ],
        ];

        foreach ($data as $value) {
            $data = Attribute::create(['status' => 1]);
            foreach (language() as $lang) {
                if (isset($value['name'][$lang->code])) {
                    $data->translation()->create(['key' => 'name', 'value' => $value['name'][$lang->code], 'language_id' => $lang->id]);
                }
            }
        }
    }
}
