<?php

namespace Modules\CoreData\Observers;

use Modules\CoreData\Entities\Category;
use Modules\CoreData\Service\MetaCategoryService;
use Modules\MasterCatalog\Actions\Product\UpdateCommissionProductAction;
class CategoryObserver
{
    /**
     * Handle the Category "created" event.
     *
     * param  \App\Models\Category  $category
     * return void
     */
    public function created(Category $category)
    {
        //todo change
        app()->make(MetaCategoryService::class)->storeByCategory($category);
    }

    /**
     * Handle the Category "updated" event.
     *
     * param  \App\Models\Category  $category
     * return void
     */
    public function updated(Category $category)
    {
        $newcommission = $category->commission; 
        $oldCommission = $category->getOriginal('commission'); 
        if($newcommission != $oldCommission){
            return (new UpdateCommissionProductAction(
                 $oldCommission,
                category: $category
            ))->execute();

        }
      
    }

    /**
     * Handle the Category "deleted" event.
     *
     * param  \App\Models\Category  $category
     * return void
     */
    public function deleted(Category $category)
    {
       
    }


    /**
     * Handle the Category "restored" event.
     *
     * param  \App\Models\Category  $category
     * return void
     */
    public function restored(Category $category)
    {
        //
    }

    /**
     * Handle the Category "force deleted" event.
     *
     * param  \App\Models\Category  $category
     * return void
     */
    public function forceDeleted(Category $category)
    {
        //
    }
}
