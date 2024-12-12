<?php

return [
    'active_dashboard' => 'dashboard-demo1', // dashboard or 'dashboard2'
    'layouts' => [
        'dashboard' => 'dashboard.layouts.app',
        'dashboard2' => 'dashboard2.layouts.app',
     'dashboard-demo1'=>'dashboard-demo1.layouts.app',
     'dashboard-demo1_supplier'=>'supplier.dashboard-demo1.layouts.app',
     'dashboard2_supplier'=>'supplier.dashboard-demo1.layouts.app',
    
    ],
    'login' => [
        'dashboard' => 'dashboard.layouts.auth.app',
        'dashboard2' => 'dashboard2.layouts.auth.app',
        'dashboard2_supplier' => 'dashboard2.layouts.auth.app',

        'dashboard-demo1' => 'dashboard2.layouts.auth.app',
    ]
];
