<?php

namespace Modules\CoreData\Actions\Notification;

use Modules\Acl\Entities\Supplier;
use Modules\CoreData\Repositories\NotificationRepository;

class SendNotificationForSupplierAction
{
    protected $repo;

    /**
     * Create a new Repository instance.
     *
     * @return void
     */
    public function __construct(NotificationRepository $repository)
    {
        //todo change
        $this->repo = $repository;
    }


    /**
     * This PHP function executes a notification by creating a new notification object, associating it
     * with a supplier, and updating the supplier's notification count.
     *
     * param title The title of the notification that will be created.
     *
     * param content The content parameter is the actual content of the notification that you want to
     * create.
     *
     * param supplier_id The ID of the supplier to associate the notification with.
     *
     * @return a boolean value. It returns true if the notification is successfully created and
     * associated with the supplier, and false if the supplier is not found.
     */
    public function execute($title, $content, $supplier_id, $urlType, $urlId, $color, $external_url = null)
    {
        $supplier = Supplier::find($supplier_id);
        if (!$supplier) {
            // Handle case when supplier is not found
            return false;
        }

        // create notification
        $notification = App(NewNotificationAction::class)->execute($title, $content, $urlType, $urlId, $color, $external_url);

        // Associate the notification with the supplier using the polymorphic relationship
        $supplier->notifications()->save($notification);

        // Update supplier's notification count
        $supplier->increment('totalNotifications');

        return true;
    }
}
