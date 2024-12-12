<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\CoreData\Entities\Category;

class CategoryTableSeeder extends Seeder
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
                'name' => ['ar' => "Fashion", 'en' => "Fashion"],
            ],
            [
                'name' => ['ar' => "Electronics", 'en' => "Electronics"],
            ],
            [
                'name' => ['ar' => "Home", 'en' => "Home"],
            ],
            [
                'name' => ['ar' => "Entertainment", 'en' => "Entertainment"],
            ],
            [
                'name' => ['ar' => "Wholesale", 'en' => "Wholesale"],
            ],
            [
                'name' => ['ar' => "Beauty & Health", 'en' => "Beauty & Health"],
            ],
        ];
        foreach ($data as $value) {
            $data = Category::create(['status' => 1]);
            foreach (language() as $lang) {
                if (isset($value['name'][$lang->code])) {
                    $data->translation()->create(['key' => 'name', 'value' => $value['name'][$lang->code], 'language_id' => $lang->id]);
                }
            }
        }
    }
}
