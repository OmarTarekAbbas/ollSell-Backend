<?php

namespace Modules\Order\Integration\Models;

use Illuminate\Database\Eloquent\Model;

class CardRegistration extends Model
{

    const SHARED_INFO = ['id', 'card', 'paymentBrand'];
}
