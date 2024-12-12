<?php

namespace Modules\Webhooks\Service;
//todo change
class EventService
{
    const ORDER_CREATED = 'order.created';
    const ORDER_UPDATED = 'order.updated';
    const ORDER_STATUS_UPDATED = 'order.status.updated';
    const ORDER_CANCELLED = 'order.cancelled';
    const ORDER_REJECTED = 'order.rejected';
    const ORDER_PRODUCTS_UPDATED = 'order.products.updated';
    const ORDER_SHIPMENT_CREATED = 'order.shipment.created';
    const ORDER_SHIPMENT_CANCELLED = 'order.shipment.cancelled';

    public static function getDescription(string $event): ?string
    {
        $descriptions = [
            self::ORDER_CREATED => 'This is triggered when an order has been created.',
            self::ORDER_UPDATED => 'This is triggered when an order has been updated.',
            self::ORDER_STATUS_UPDATED => 'This is triggered when an order status has been updated.',
            self::ORDER_CANCELLED => 'This is triggered when an order has been cancelled.',
            self::ORDER_REJECTED => 'This is triggered when an order has been rejected.',
            self::ORDER_PRODUCTS_UPDATED => 'This is triggered when an order products have been updated.',
            self::ORDER_SHIPMENT_CREATED => 'This is triggered when an order shipment return has been created.',
            self::ORDER_SHIPMENT_CANCELLED => 'This is triggered when an order shipment return has been cancelled.',
        ];

        return $descriptions[$event] ?? null;
    }

    public static function getAllEvents()
    {
        return [
            self::ORDER_CREATED,
            self::ORDER_UPDATED,
            self::ORDER_STATUS_UPDATED,
            self::ORDER_CANCELLED,
            self::ORDER_REJECTED,
            self::ORDER_PRODUCTS_UPDATED,
            self::ORDER_SHIPMENT_CREATED,
            self::ORDER_SHIPMENT_CANCELLED,
        ];
    }

    public static function getAllEventsWithDescriptions()
    {
        $events = collect();

        foreach (self::getAllEvents() as $event) {
            $description = self::getDescription($event);
            $events->push(['event' => $event, 'description' => $description]);
        }

        return $events;
    }

}

    // single webhook
    // $event = EventService::ORDER_CREATED;
    // $description = EventService::getDescription($event);

    // all webhooks
    // $allEvents = EventService::getAllEvents();