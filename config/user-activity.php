<?php

return [
    'activated'        => true, // active/inactive all logging
    'middleware'       => ['web', 'auth', 'api'],
    'route_path'       => 'admin/user-activity',
    'admin_panel_path' => 'dashboard/index',
    'delete_limit'     => 7, // default 7 days

    'model' => [
        'user' => "App\Models\User",
        'dropshipper' => "Modules\Acl\Entities\Dropshipper"
    ],

    'log_events' => [
        'on_create'     => true,
        'on_edit'       => true,
        'on_delete'     => true,
        'on_login'      => true,
        'on_lockout'    => true
    ]
];
