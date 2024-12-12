<?php

namespace Modules\StoreIntegrations\Http\Controllers\Salla;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Modules\StoreIntegrations\Http\Requests\WebhookRequest;

class WebhookController extends Controller
{
    public function __invoke(WebhookRequest $request)
    {
        Log::channel('salla')->info(
            'Salla Webhook',
            $request->all()
        );

        $event = explode('.', $request->get('event'));
        $component = $event[0];
        $action = Str::camel(Str::replace('.', '_', Str::after($request->get('event'), $component.'.')));

        $classOfAction = sprintf('\\Modules\\StoreIntegrations\\Actions\\%s\\%s', ucfirst($component), ucfirst($action));
        if (!class_exists($classOfAction)) {
            return response('Ok, but without process');
        }

        $classOfAction::make()->setRequest($request)->handle();
        return response('🎉');
    }
}
