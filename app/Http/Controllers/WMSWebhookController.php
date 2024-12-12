<?php

namespace App\Http\Controllers;

use App\Http\Requests\WMSWebhookRequest;

use Illuminate\Support\Str;

class WMSWebhookController extends Controller
{
    public function __invoke(WMSWebhookRequest $request)
    {
      
        $event = explode('.', $request->get('event_name'));
        $component = $event[0];
        $action = Str::camel(Str::replace('.', '_', Str::after($request->get('event_name'), $component.'.')));
     
        $classOfAction = sprintf('\\App\\ActionsWMS\\%s\\%s', ucfirst($component), ucfirst($action));
    
        if (!class_exists($classOfAction)) {
            return response('Ok, but without process');
        }

        $classOfAction::make()->setRequest($request)->handle();
        return response('🎉');
    }
}
