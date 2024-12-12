<?php

namespace Modules\Order\Actions\Order;

use Modules\Order\Entities\Fail;


class FailOrderAction
{

    public function execute(array $data)
    {
        $new =  Fail::updateOrCreate($data);

        if ($new) {
            return true;
        }

        return false;
    }
}
