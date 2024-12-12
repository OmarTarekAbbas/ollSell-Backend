<?php

namespace Modules\Acl\Observers;

use Modules\Acl\Entities\Dropshipper;
use Modules\MasterCatalog\Service\ProfitService;

class DropshipperObserver
{
    /**
     * Handle the Dropshipper "created" event.
     *
     * param  \App\Models\Dropshipper  $dropshipper
     * return void
     */
    public function created(Dropshipper $dropshipper)
    {
    }

    /**
     * Handle the Dropshipper "updated" event.
     *
     * param  \App\Models\Dropshipper  $dropshipper
     * return void
     */
    public function updated(Dropshipper $dropshipper)
    {
        if (user()) {
            //todo change
            app()->make(ProfitService::class)->storeByUpdateProfitDropShipper($dropshipper);
        }
    }

    /**
     * Handle the Dropshipper "deleted" event.
     *
     * param  \App\Models\Dropshipper  $dropshipper
     * return void
     */
    public function deleted(Dropshipper $dropshipper)
    {
        //
    }

    /**
     * Handle the Dropshipper "restored" event.
     *
     * param  \App\Models\Dropshipper  $dropshipper
     * return void
     */
    public function restored(Dropshipper $dropshipper)
    {
        //
    }

    /**
     * Handle the Dropshipper "force deleted" event.
     *
     * param  \App\Models\Dropshipper  $dropshipper
     * return void
     */
    public function forceDeleted(Dropshipper $dropshipper)
    {
        //
    }
}
