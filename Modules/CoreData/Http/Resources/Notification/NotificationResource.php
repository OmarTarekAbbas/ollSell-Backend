<?php

namespace Modules\CoreData\Http\Resources\Notification;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\CoreData\Entities\Notification;
use Modules\CoreData\Traits\StatusIconNotification;
class NotificationResource extends JsonResource
{
    /**
     * This PHP function converts an object into an array with specific key-value pairs.
     *
     * param request  is an instance of the Illuminate\Http\Request class, which represents an
     * incoming HTTP request. It contains information about the request such as the HTTP method,
     * headers, and query parameters. In this context, it is being passed as an argument to the
     * toArray() method, which is used to convert a model
     *
     * return An array with the user's ID, name, and avatar. If the user has an avatar, the function
     * will return the file path to the avatar. If the user does not have an avatar, it will return a
     * default blank avatar.
     */
    public function toArray($request)
    {
        $title = json_decode($this->title);
        $content = json_decode($this->content);

        return [
            'id' => $this->id,
            'title' => $title->{user()->lang} ?? "",
            'content' => $content->{user()->lang} ?? "",
            'user_id' => $this->user_id ?? "",
            'user_type' => ($this->user_type == Notification::DROPSHIPPER) ? 'dropshipper' : 'suppler' ?? "",
            'urlId' => $this->url_id ?? "",
            'urlType' => $this->url_type ?? "",
            'Icon' => StatusIconNotification::statusIcon($this->url_type),
            'color' => $this->color ?? "",
            'seen' => $this->seen ?? "",
            'seenAt' => $this->seenAt ? $this->seenAt->format('Y-m-d H:i') : now()->format('Y-m-d H:i'),
            'created_at' => $this->created_at->diffForHumans() ?? "",
        ];
    }
}
