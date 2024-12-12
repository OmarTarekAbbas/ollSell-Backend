<?php

namespace Modules\Order\Actions\Order;

use Modules\Order\Entities\Fake;
use Modules\Order\Entities\Order;
use Modules\Order\Enums\OrderEnum;

class FakeNumberOrderAction
{
    /**
     * The function marks an order as fake and creates a corresponding entry in the "Fake" table.
     *
     * @param int order_id The `execute` function takes an integer parameter ``, which is used
     * to find an order by its ID. The function then sets the `is_fake` attribute of the order to 1 and
     * saves the order. If the order is successfully saved, a new `Fake` object is created
     *
     * @return The `execute` function returns a boolean value. It returns `true` if the order is
     * successfully saved and a new `Fake` record is created, otherwise it returns `false`.
     */
    public function execute($customerPhone): bool
    {
        $existingFake = Fake::where('customerPhone', $customerPhone)->first();

        // If there is an existing fake record, mark the order as fake
        if ($existingFake) {
            return true;
        }

        $orders = Order::where('customerPhone', $customerPhone)
            ->whereIn('status_id', [OrderEnum::REJECTED_STATUS, OrderEnum::CANCELED_STATUS])
            ->where(function ($query) {
                $query->where('sub_status_id', '!=', 2);
            })
            ->count();

        // Get the fake number threshold from the settings or default to 5
        $fakeNumber = setting('fake_number') ?? 5;
        // If the number of rejected/canceled orders exceeds the threshold, mark the order as fake
        if ($orders >= (int)$fakeNumber) {
            // Create a new fake record
            $fake = new Fake();
            $fake->customerPhone = $customerPhone;
            $fake->save();

            return true;
        }

        return false;
    }
}
