<?php

namespace Modules\CoreData\Service;

use Illuminate\Http\Request;
use Modules\Basic\Service\BasicService;
use Modules\CoreData\Repositories\NotificationRepository;
use Modules\CoreData\Actions\Notification\MarkAllAsSeenAction;
use Modules\CoreData\Http\Resources\Notification\NotificationResource;
//todo change
class NotificationService extends BasicService
{
    protected NotificationRepository $repo;

    /**
     * Create a new Repository instance.
     *
     * return void
     */
    public function __construct(NotificationRepository $repository)
    {
        $this->repo = $repository;
    }

    /**
     * This PHP function returns a collection of NotificationResource objects based on a list of
     * parameters.
     *
     * param Request request The HTTP request object that contains information about the current
     * request being made.
     *
     * param pagination A boolean value that determines whether the results should be paginated or
     * not. If set to true, the results will be paginated based on the  parameter.
     *
     * param perPage The number of items to be displayed per page in the paginated list. In this case,
     * it is set to 10.
     *
     * param recursiveRel The  parameter is an array that specifies the relationships
     * that should be recursively loaded when retrieving the categories.
     *
     * return a collection of NotificationResource objects. The collection is obtained by calling the
     * "list" method of the repository object with the provided parameters.
     */
    public function list(Request $request, $pagination = false, $perPage = 10, $recursiveRel = [])
    {
        return NotificationResource::collection($this->repo->list($request, [], $recursiveRel, $pagination, $perPage));
    }

    /**
     * This function finds records based on specified parameters and returns them.
     *
     * param Request request This parameter is an instance of the Request class, which is used to
     * retrieve data from the HTTP request.
     *
     * param pagination A boolean value indicating whether or not to paginate the results. If set to
     * true, the results will be paginated based on the  parameter. If set to false, all
     * results will be returned.
     *
     * param perPage The number of records to be displayed per page in case of pagination.
     *
     * param pluck An array of columns to retrieve from the database. If provided, only the specified
     * columns will be returned in the result set.
     *
     * param get The "get" parameter is used to specify the columns that should be retrieved from the
     * database. It can be an array of column names or a string of comma-separated column names.
     *
     * param moreConditionForFirstLevel moreConditionForFirstLevel is an optional parameter that
     * allows you to add additional conditions to the first level of the query.
     *
     * param recursiveRel The recursiveRel parameter is an array that specifies the relationships that
     * should be recursively loaded when retrieving the data.
     *
     * param withRelations withRelations is an array of relationships that should be eager loaded when
     * retrieving the data.
     *
     * param latest The "latest" parameter is used to specify the column to order the results by in
     * descending order. This means that the most recent records will be returned first.
     *
     * param limit The limit parameter is used to limit the number of results returned by the query.
     * If set to 0, it means there is no limit and all matching results will be returned
     *
     * return the result of calling the `findBy` method on the repository object with the provided
     * arguments.
     */
    public function findBy(Request $request, $pagination = false, $perPage = 10, $pluck = [], $get = '', $moreConditionForFirstLevel = [], $recursiveRel = [], $withRelations = [], $latest = '', $limit = 0)
    {
        return $this->repo->findBy($request, $pagination, $perPage, $pluck, $get, $moreConditionForFirstLevel, $recursiveRel, $withRelations, latest: $latest, limit: $limit);
    }

    /**
     * The function "markAllAsSeen" executes the "MarkAllAsSeenAction" class with the provided user ID
     * and user type.
     *
     * param user_id The user_id parameter is the unique identifier of the user for whom we want to
     * mark all notifications as seen. It could be an integer or a string, depending on how the user is
     * identified in the system.
     *
     * param user_type The user_type parameter is used to specify the type of user for whom the
     * notifications should be marked as seen. It could be a string or an integer value that represents
     * the user type.
     *
     * return the result of executing the `execute` method of the `MarkAllAsSeenAction` class.
     */
    public function markAllAsSeen($user_id, $user_type)
    {
        return App(MarkAllAsSeenAction::class)->execute($user_id, $user_type);
    }

    /**
     * The function "deleteNotification" deletes a notification record from the database.
     *
     * param id The id parameter is the unique identifier of the notification record that should be
     * deleted. It is used to identify the specific notification record that should be removed from the
     * database.
     *
     * return the result of calling the `delete` method on the repository object with the provided
     * notification ID.
     */
    public function deleteNotification($id)
    {
        $notification = $this->repo->find($id);

        if($notification->user_id == user()->id) {
            return $this->repo->delete($id);
        }

        return false;
    }
}
