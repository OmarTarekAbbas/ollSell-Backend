<?php

namespace Modules\CoreData\Actions\Notification;

use App\Models\User;
use Modules\CoreData\Repositories\NotificationRepository;

class SendNotificationByAdminAction
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
    public function execute($title, $content, $urlType, $urlId, $color, $external_url = null, $admin_id = null)
    {
        // create notification
        $notification = App(NewNotificationAction::class)->execute($title, $content, $urlType, $urlId, $color, $external_url);
        // Here, replace hardcoded admin with the admin instance or retrieve it dynamically
        if ($admin_id) {
            $admin = User::find($admin_id);
            // Associate the notification with the admin using the polymorphic relationship
            $admin->notifications()->save($notification);
            // Update admin's notification count
            $admin->increment('totalNotifications');
        } else {
            $admins = User::withRole(1)->get();

            foreach ($admins as $admin) {
                if ($admin->role && $admin->role->role_id == 1) {
                    // Associate the notification with the admin using the polymorphic relationship
                    $admin->notifications()->save($notification);
                    // Update admin's notification count
                    $admin->increment('totalNotifications');
                }
            }
        }
    }
}
