<?php

namespace Modules\Webhooks\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Webhooks\Entities\Webhook;
use Modules\Webhooks\Service\EventService;
use Modules\Basic\Http\Controllers\BasicController;
use Modules\Webhooks\Http\Requests\Api\CreateOrUpdateWebhookRequest;
//todo change
class WebhooksController extends BasicController
{
    public function eventsList(Request $request)
    {
        $events = EventService::getAllEventsWithDescriptions();

        return $this->apiResponse(
            data: $events
        );
    }

    public function index()
    {
        $webhooks = Webhook::where('dropshipper_id', user()->id)->get();

        return $this->apiResponse(
            data: $webhooks
        );
    }

    public function store(CreateOrUpdateWebhookRequest $request)
    {
        $user = user();
        $event = $request->input('event');
        if (!in_array($event, EventService::getAllEvents())) {
            return $this->apiValidation('Invalid event provided.');
        }

        $webhook = Webhook::where('dropshipper_id', $user->id)
            ->where('event', $event)
            ->first();

        if ($webhook) {
            $webhook->update(['url' => $request->input('url')]);
        } else {
            $webhook = Webhook::create([
                'dropshipper_id' => $user->id,
                'event' => $event,
                'url' => $request->input('url'),
            ]);
        }

        return $this->apiResponse(
            data: $webhook,
            message: 'Webhook settings updated successfully',
        );
    }

    public function destroy($event)
    {
        $user = user();
        Webhook::where('dropshipper_id', $user->id)
            ->where('event', $event)
            ->delete();

        return $this->apiResponse(
            message: 'Webhook settings removed successfully',
        );
    }
}
