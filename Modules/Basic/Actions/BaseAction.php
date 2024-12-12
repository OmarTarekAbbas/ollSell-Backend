<?php

namespace Modules\Basic\Actions;

use Lorisleiva\Actions\Concerns\AsAction;
use Modules\StoreIntegrations\Http\Requests\WebhookRequest;

abstract class BaseAction
{
    use AsAction;

    /**
     * @var WebhookRequest
     */
    protected $request;

    public function setRequest(WebhookRequest $request)
    {
        $this->request = $request;

        return $this;
    }

    public function __get($name)
    {
        return $this->request->get($name);
    }
}
