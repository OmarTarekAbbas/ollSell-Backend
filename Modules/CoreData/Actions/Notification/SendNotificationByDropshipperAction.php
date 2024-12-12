<?php

namespace Modules\CoreData\Actions\Notification;

use Modules\Acl\Entities\Dropshipper;
use Modules\CoreData\Repositories\NotificationRepository;

class SendNotificationByDropshipperAction
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
     * This PHP function creates a new notification, associates it with an admin user, and updates the
     * admin's notification count.
     * 
     * param title The title of the notification that you want to create and associate with the admin.
     * 
     * param content The content parameter is the actual content or message of the notification that
     * you want to send. 
     */
    public function execute($title, $content, $dropshipper_id, $urlType, $urlId, $color)
    {
        $dropshipper = Dropshipper::find($dropshipper_id);
        if (!$dropshipper) {
            // Handle case when dropshipper is not found
            return false;
        }

        // create notification
        $notification = App(NewNotificationAction::class)->execute($title, $content, $urlType, $urlId, $color);

        // Associate the notification with the dropshipper using the polymorphic relationship
        $dropshipper->notifications()->save($notification);

        // Update dropshipper's notification count
        $dropshipper->increment('totalNotifications');

        return true;
    }
}
