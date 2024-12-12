<?php

namespace Modules\Order\Actions\Order;

use App\Services\OllopsClientService;

class GetMessageTemplatesAction
{
    protected $ollopsClient;
    protected $payload;

    public function __construct($payload = null)
    {
        $this->payload = $payload;
    }

    public function execute()
    {
        $this->ollopsClient = app(OllopsClientService::class);

        $response = $this->ollopsClient->getMessageTemplates($this->payload);

        if ($response->getStatusCode() == 200) {
            $responseData = json_decode($response->getBody()->getContents(), true);
            return $responseData;
        } else {
            // Handle error
            dd("ops");
        }
    }

}
