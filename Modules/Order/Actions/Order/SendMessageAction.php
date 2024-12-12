<?php

namespace Modules\Order\Actions\Order;

use App\Services\OllopsClientService;

class SendMessageAction
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

        $response = $this->ollopsClient->sendMessage($this->payload);

        if ($response->getStatusCode() == 200) {
            $responseData = json_decode($response->getBody()->getContents(), true);
            return $responseData;
        } else {
            // Handle error
            return json_encode(['error' => 'Something went wrong']);
        }
    }

}
