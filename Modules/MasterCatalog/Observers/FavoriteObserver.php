<?php

namespace Modules\MasterCatalog\Observers;

use Modules\MasterCatalog\Entities\Favorite;
use Modules\MasterCatalog\Service\ProfitService;

class FavoriteObserver
{
    /**
     * Handle the Favorite "created" event.
     *
     * param  \App\Models\Favorite  $favorite
     * return void
     */
    public function created(Favorite $favorite)
    {//todo change
         app()->make(ProfitService::class)->storeByFavorite($favorite);
    }

    /**
     * Handle the Favorite "updated" event.
     *
     * param  \App\Models\Favorite  $favorite
     * return void
     */
    public function updated(Favorite $favorite)
    {
        //
    }

    /**
     * Handle the Favorite "deleted" event.
     *
     * param  \App\Models\Favorite  $favorite
     * return void
     */
    public function deleted(Favorite $favorite)
    {
        //
    }

    /**
     * Handle the Favorite "restored" event.
     *
     * param  \App\Models\Favorite  $favorite
     * return void
     */
    public function restored(Favorite $favorite)
    {
        //
    }

    /**
     * Handle the Favorite "force deleted" event.
     *
     * param  \App\Models\Favorite  $favorite
     * return void
     */
    public function forceDeleted(Favorite $favorite)
    {
        //
    }
}
