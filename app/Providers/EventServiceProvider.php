<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Acl\Entities\Dropshipper;
use Modules\Acl\Events\onboardingEmail;
use Modules\Acl\Listeners\SendMailOnboarding;
use Modules\Acl\Observers\DropshipperObserver;
use Modules\CoreData\Entities\Category;
use Modules\CoreData\Observers\CategoryObserver;
use Modules\MasterCatalog\Entities\Favorite;
use Modules\MasterCatalog\Observers\FavoriteObserver;
use Modules\Order\Entities\Order;
use Modules\Order\Entities\PendingOrder;
use Modules\Order\Observers\OrderObserver;
use Modules\Order\Observers\PendingOrderImportObserver;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        onboardingEmail::class => [
            SendMailOnboarding::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * return void
     */
    public function boot()
    {
        Favorite::observe(FavoriteObserver::class);
        Dropshipper::observe(DropshipperObserver::class);
        Order::observe(OrderObserver::class);
        PendingOrder::observe(PendingOrderImportObserver::class);
        Category::observe(CategoryObserver::class);
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
}
