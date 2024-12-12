<?php

namespace Modules\CoreData\Actions\Language;

use Modules\Basic\Entities\Translation;
use Modules\MasterCatalog\Entities\Product;

class LanguageAction
{
    protected $repo;

    /**
     * Create a new Repository instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * This function executes an order by calculating the total quantity, total price, total VAT, cost
     * price, weight, and shipping fees, and then saves the order data.
     * 
     * @return a boolean value. If the data is successfully saved, it will return true. Otherwise, it
     * will return false.
     */
    public function execute()
    {
        //todo change
        foreach (Product::all() as $product) {

            $arabicTranslations = Translation::where('category_type', 'Modules\MasterCatalog\Entities\Product')
                ->where('category_id', $product->id)
                ->where('language_id', 2) // Assuming Arabic language_id is 2
                ->get();

            // Update English translations with Arabic values
            foreach ($arabicTranslations as $translation) {
                $englishTranslation = Translation::where('category_type', 'Modules\MasterCatalog\Entities\Product')
                    ->where('category_id', $product->id)
                    ->where('language_id', 1) // Assuming English language_id is 1
                    ->where('key', $translation->key) // Assuming 'name' or 'description'
                    ->first();

                // Update English translation with the Arabic value
                if ($englishTranslation) {
                    $englishTranslation->value = $translation->value;
                    $englishTranslation->save();
                } else {
                    // If an English translation doesn't exist, create one
                    Translation::create([
                        'category_type' => 'Modules\MasterCatalog\Entities\Product',
                        'category_id' => $product->id,
                        'key' => $translation->key,
                        'language_id' => 1, // English language_id
                        'value' => $translation->value,
                    ]);
                }
            }
        }
    }
}
