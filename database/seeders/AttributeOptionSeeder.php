<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\MasterCatalog\Entities\AttributeOption;

class AttributeOptionSeeder extends Seeder
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
                'name' => ['ar' => "أحمر", 'en' => "Red"],
                'attribute_id' => 6,
            ],
            [
                'name' => ['ar' => "أخضر", 'en' => "Green"],
                'attribute_id' => 6,

            ],
            [
                'name' => ['ar' => "أزرق", 'en' => "Blue"],
                'attribute_id' => 6,
            ],

            [
                'name' => ['ar' => "صغير", 'en' => "Small"],
                'attribute_id' => 5,
            ],
            [
                'name' => ['ar' => "متوسط", 'en' => "Medium"],
                'attribute_id' => 5,
            ],
            [
                'name' => ['ar' => "كبير", 'en' => "Large"],
                'attribute_id' => 5,
            ],
        ];

        foreach ($data as $value) {
            $data = AttributeOption::create([
                'attribute_id' => $value['attribute_id']
            ]);
            foreach (language() as $lang) {
                if (isset($value['name'][$lang->code])) {
                    $data->translation()->create(['key' => 'name', 'value' => $value['name'][$lang->code], 'language_id' => $lang->id]);
                }
            }
        }
    }
}
