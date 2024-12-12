<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\CoreData\Entities\Language;

class LanguageTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Language::create(
            [
                'name' => 'English',
                'code' => 'en',
                'order' => 1,
                'status' => 1,
            ],
        );
        Language::create(
            [
                'name' => 'العربية',
                'code' => 'ar',
                'order' => 2,
                'status' => 1,
            ]
        );
    }
}
