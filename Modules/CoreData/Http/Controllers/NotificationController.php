<?php

namespace Modules\CoreData\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Modules\CoreData\Service\NotificationService;
use Modules\Basic\Http\Controllers\BasicController;
use Modules\CoreData\Entities\Notification;
//todo change
class NotificationController extends BasicController
{
    protected NotificationService $service;

    /**
     * This function constructs a NotificationService object and sets middleware permissions for various
     * category-related actions.
     *
     * param NotificationService Service The  parameter is an instance of the NotificationService
     * class, which is likely responsible for handling business logic related to categories in the
     * application. It is being injected into the constructor using dependency injection.
     */
    public function __construct(NotificationService $Service)
    {
        $this->service = $Service;
    }

    /**
     * @throws Exception
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $user_id = 1;
            $user_type = Notification::ADMIN;
            $request->merge(['user_id' => $user_id,  'user_type' => $user_type]);
            return DataTables::of(
                $this->service->findBy($request)
            )->make(true);
        }
        return $this->getDashboardView('coredata::category.index');
    }

     /**
     * The function "markAllAsSeen" marks all notifications as seen for a specific user.
     *
     * param user_id The user ID is a unique identifier for a user in the system. It is used to
     * identify the specific user whose notifications are being marked as seen.
     * param user_type The user_type parameter is used to specify the type of user for whom the
     * notifications should be marked as seen. It could be a string or an integer value that represents
     * the user type.
     *
     * return the result of the `markAllAsSeen` method of the `service` object, which is being passed
     * the `` and `` parameters. The result is then being passed to the `apiResponse`
     * method, and the final result of that method is being returned.
     */
    public function markAllAsSeen()
    {
        return $this->apiResponse($this->service->markAllAsSeen(auth()->user()->id, Notification::ADMIN));
    }

    public function markNotificationAsSeen(Request $request)
    {
        $notificationId = $request->input('notification_id');

        $notification = Notification::find($notificationId);

        if ($notification) {
            $notification->update([
                'seenAt' => now(),
                'seen'   => 1,
            ]);

            return response()->json(['message' => 'Notification marked as seen'], 200);
        }

        return response()->json(['error' => 'Notification not found'], 404);
    }
}
