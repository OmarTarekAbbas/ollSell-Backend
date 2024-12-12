<?php

namespace Modules\CoreData\Actions\MetaCategory;

use Modules\CoreData\Entities\MetaCategory;
use Modules\CoreData\Repositories\CityRepository;

class MetaCategoryAction
{
    protected Request $request;
    protected  $category;
    /**
     * Create a new Repository instance.
     *
     * @return void
     */
    public function __construct(Request $request,$category)
    {
        $this->request=$request;
        $this->category=$category;
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
        if ($this->category->id) {
            $metaCategories = MetaCategory::where('category_id', $this->category->id)->get();
            foreach ($metaCategories as $metaCategory) {
                $metaCategory->delete();
            }
        }

        if ($this->request->multiMeta) {
            $multiMeta = $this->request->multiMeta;
            $jsonMultiMeta = json_decode($multiMeta, true);
            foreach ($jsonMultiMeta as $meta) {
                $this->request->merge([
                    'name' => $meta['value'],
                    'category_id' =>  $this->category->id,
                ]);
                $data =App(CityRepository::class)->save($this->request);
            }
            if ($data) {
                return true;
            }
            return false;
        }
        return true;      
    }
}
