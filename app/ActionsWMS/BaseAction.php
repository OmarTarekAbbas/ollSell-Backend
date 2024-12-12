<?php

namespace App\ActionsWMS;
use App\Http\Requests\WMSWebhookRequest;
use Lorisleiva\Actions\Concerns\AsAction;

abstract class BaseAction
{
    use AsAction;

    /**
     * @var WebhookRequest
     */
    protected $request;

    public function setRequest(WMSWebhookRequest $request)
    {
        $this->request = $request;

        return $this;
    }

    public function __get($name)
    {
        return $this->request->get($name);
    }
}
