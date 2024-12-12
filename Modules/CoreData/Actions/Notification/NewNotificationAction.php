<?php

namespace Modules\CoreData\Actions\Notification;

use Modules\CoreData\Entities\Notification;
use Modules\CoreData\Repositories\NotificationRepository;

class NewNotificationAction
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
     * The function creates a new notification object with the given title and content and returns it.
     *
     * param title The title parameter is a string that represents the title of the notification. It
     * is used to set the title property of the Notification object.
     *
     * param content The content parameter is a string that represents the content of the
     * notification. It can be any text or message that you want to include in the notification.
     *
     * @return an instance of the Notification class with the title and content properties set to the
     * values passed as arguments.
     */
    public function execute($title, $content, $urlType, $urlId, $color, $external_url = null)
    {
        $notification = new Notification();
        $notification->title = $title;
        $notification->content = $content;
        $notification->url_id = $urlId;
        $notification->url_type = $urlType;
        $notification->color = $color;
        $notification->external_url = $external_url;

        return $notification;
    }
}
