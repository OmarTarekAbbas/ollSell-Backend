<?php

namespace Modules\Acl\Http\Controllers;

use Modules\Basic\Http\Controllers\BasicController;

class ActivityController extends BasicController
{
    public function index()
    {
        return $this->getDashboardView('acl::activity');
    }
}
