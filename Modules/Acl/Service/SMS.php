<?php

// Copyright
declare(strict_types=1);

namespace Modules\Acl\Service;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
//todo change
class SMS
{
    /**
     * Gateway Settings
     *
     * @var array
     */
    private $baseUrl;
    private $clientId;
    private $clientPassword;
    private $httpClient;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->baseUrl = env('UNIFIED_CLIENT_URL');
        $this->clientId = env('UNIFIED_CLIENT_ID');
        $this->clientPassword = env('UNIFIED_CLIENT_PASSWORD');
        $this->httpClient = new Client();
    }

    /**
     * The function `sendSms` sends an SMS message using the specified parameters and handles any
     * exceptions or errors that occur during the process.
     * 
     * param to The phone number of the recipient of the SMS message.
     * param from The "from" parameter represents the sender of the SMS message. It can be a phone
     * number or a text string that identifies the sender.
     * param text The "text" parameter is the content of the SMS message that you want to send. It
     * should be a string containing the actual text message that you want to send to the recipient.
     * param dlrUrl The dlrUrl parameter is an optional parameter that specifies the URL where delivery
     * reports for the SMS will be sent. If provided, the SMS gateway will send a delivery report to
     * this URL once the SMS has been delivered or failed to be delivered.
     * param category The "category" parameter is an optional parameter that can be used to categorize
     * the SMS message. It can be used to group messages based on a specific category or purpose. This
     * parameter is useful when you want to track and analyze the performance of different categories of
     * messages.
     * 
     * @return the result of the `parseResponse` method if the request is successful. If there is an
     * exception, it is returning the result of the `handleError` method.
     */
    public function send($phoneNumber,$from ,$message, $dlrUrl = null, $category = null)
    {
        $queryParameters = [
            'clientid' => $this->clientId,
            'clientpassword' => $this->clientPassword,
            'to' => $phoneNumber,
            'from' => $from,
            'text' => $message,
        ];
        if ($dlrUrl) {
            $queryParameters['dlr-url'] = $dlrUrl;
        }

        if ($category) {
            $queryParameters['category'] = $category;
        }

        try {
            $response = $this->httpClient->get($this->baseUrl, [
                'query' => $queryParameters,
            ]);

            return $this->parseResponse($response->getBody()->getContents());
        } catch (RequestException $e) {
            // Handle exceptions, including 409 Conflict
            $statusCode = $e->getResponse()->getStatusCode();
            $errorResponse = $e->getResponse()->getBody()->getContents();

            return $this->handleError($statusCode, $errorResponse);
        }
        
    }

    /**
     * The function "parseResponse" takes a response string, parses it into an array, and returns
     * specific values from the array.
     * 
     * param response The response parameter is a string that contains key-value pairs separated by an
     * ampersand (&). Each key-value pair represents a parameter and its value.
     * 
     * @return an array with the following keys and values:
     */
    private function parseResponse($response)
    {
        parse_str($response, $responseData);
        return [
            'guid' => $responseData['guid'] ?? null,
            'mobilenumber' => $responseData['mobilenumber'] ?? null,
            'statuscode' => $responseData['statuscode'] ?? null,
            'statustext' => $responseData['statustext'] ?? null,
        ];
    }

    /**
     * The function "handleError" returns an error response with the specified status code and error
     * message.
     * 
     * param statusCode The statusCode parameter is the HTTP status code that indicates the type of
     * error that occurred. It is typically a numeric value such as 400 for Bad Request or 500 for
     * Internal Server Error.
     * param errorResponse The error message or response that you want to return when an error
     * occurs.
     * 
     * @return An array is being returned with the following keys and values:
     * - 'error' => true
     * - 'status_code' =>  (the value of the  parameter)
     * - 'error_message' =>  (the value of the  parameter)
     */
    private function handleError($statusCode, $errorResponse)
    {
        return [
            'error' => true,
            'status_code' => $statusCode,
            'error_message' => $errorResponse,
        ];
    }
}
