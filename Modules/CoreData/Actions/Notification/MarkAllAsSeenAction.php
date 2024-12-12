<?php

namespace Modules\CoreData\Actions\Notification;

use Carbon\Carbon;
use App\Models\User;
use Modules\Acl\Entities\Dropshipper;
use Modules\Acl\Entities\Supplier;
use Modules\CoreData\Entities\Notification;
use Modules\CoreData\Repositories\NotificationRepository;

class MarkAllAsSeenAction
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
     * The function updates the notifications for a user and sets the total notifications count to 0
     * for either a dropshipper or an admin user.
     *
     * param user_id The user ID is a unique identifier for a user in the system. It is used to
     * identify the specific user for whom the notifications are being processed.
     *
     * param user_type The user_type parameter is used to determine the type of user for whom the
     * notifications are being fetched and updated.
     *
     * @return a boolean value of true.
     */
    public function execute($user_id, $user_type)
    {
        $notifications = Notification::where('user_id', $user_id)->where('user_type', $user_type)->whereNull('seenAt')->get();

        foreach ($notifications as $notification) {
            $notification->seen = 1;
            $notification->seenAt = now();
            $notification->save();
        }

        if ($user_type === Notification::DROPSHIPPER) {
            $dropshipper = Dropshipper::find($user_id);
            $dropshipper->totalNotifications = 0;
            $dropshipper->save();
        } elseif ($user_type === Notification::SUPPLIER) {
            $dropshipper = Supplier::find($user_id);
            $dropshipper->totalNotifications = 0;
            $dropshipper->save();
        } else {
            $admin = User::find(1);
            $admin->totalNotifications = 0;
            $admin->save();
        }

        return true;
    }
}
