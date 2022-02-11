<?php
namespace Avido\Smtpeter\Endpoints;

use Avido\Smtpeter\Client;
use Avido\Smtpeter\Exceptions\SmtpeterException;

abstract class BaseEndpoint
{
    /** @var Client  */
    protected $apiClient;

    public function __construct(Client $client)
    {
        $this->apiClient = $client;

        $this->boot();
    }

    protected function boot(): void
    {
    }


    /**
     * Performs a HTTP call to the API endpoint.
     *
     * @param string $httpMethod
     * @param string $apiMethod
     * @param string|null $httpBody
     * @param array $arguments
     * @param array $requestHeaders
     * @return mixed|string|void
     * @throws SmtpeterException
     */
    protected function performApiCall(
        string $httpMethod,
        string $apiMethod,
        ?string $httpBody = null,
        array $arguments = [],
        array $requestHeaders = []
    ) {
        $response = $this->apiClient->performHttpCall($httpMethod, $apiMethod, $httpBody, $arguments, $requestHeaders);

        $body = $response->getBody()->getContents();

        if (empty($body)) {
            if ($response->getStatusCode() === Client::HTTP_STATUS_NO_CONTENT) {
                return;
            }

            throw new SmtpeterException('No response body found.');
        }

        if ($response->getStatusCode() >= 400) {
            throw new SmtpeterException($body, $response->getStatusCode());
        }

        if (collect($response->getHeader('Content-Type'))->first() !== 'application/json') {
            return $response->getBody()->getContents();
        }

        $object = @json_decode($body);
        if (json_last_error() != JSON_ERROR_NONE) {
            throw new SmtpeterException("Unable to decode smtpeter response: '{$body}'.");
        }

//            $error = collect(collect($object->errors)->first());
//
//            $messageBag = collect('Error executing API call');
//
//            if ($error->has('code')) {
//                $messageBag->push('('.$error->get('code').')');
//            }
//
//            if ($error->has('message')) {
//                $messageBag->push(': '.$error->get('message'));
//            }
//
//            if ($error->has('human')) {
//                $messageBag->push(': '.collect($error->get('human'))->first());
//            }
//
//            throw new MyParcelException($messageBag->implode(' '), $response->getStatusCode());
//        }

        return $object;
    }
}
