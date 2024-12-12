<?php

namespace Modules\CoreData\Http\Controllers\Api;

use Illuminate\Http\Request;
use Modules\Basic\Http\Controllers\BasicController;
use Modules\CoreData\Entities\Notification;
use Modules\CoreData\Service\NotificationService;
//todo change
class NotificationController extends BasicController
{
    private $service;

    /**
     * This is a constructor function that initializes a NotificationService object.
     *
     * param NotificationService Service The parameter "Service" is an instance of the NotificationService
     * class, which is being injected into the constructor of the current class. This is a common
     * practice in dependency injection, where the dependencies of a class are passed in as constructor
     * parameters rather than being instantiated within the class itself. This allows for better
     */
    public function __construct(NotificationService $Service)
    {
        $this->service = $Service;
    }

    /**
     * List Categories
     *
     * The List Categories endpoint allows users to retrieve a list of categories available within the system.
     * This endpoint provides users with an overview of the available categories for products or services.
     *
     * This endpoint retrieves the list of categories available within the system.
     * The API will respond with the category information, including the category name, description, and any other relevant details.
     *
     */
    public function list(Request $request)
    {
        $user_id = user()->id;
        $user_type = Notification::DROPSHIPPER;
        $request->merge(['user_id' => $user_id,  'user_type' => $user_type]);
        return $this->apiResponse($this->service->list($request, $this->pagination(), $this->perPage()));
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
    public function markAllAsSeen($user_id, $user_type)
    {
        return $this->apiResponse($this->service->markAllAsSeen($user_id, $user_type));
    }

    public function deleteNotification($id)
    {
        if($this->service->deleteNotification($id)) {
            return $this->apiResponse(true);
        }

        return $this->apiResponse(false, 400);
    }
}
