<?php
namespace Avido\Smtpeter;

use Avido\Smtpeter\Exceptions\SmtpeterException;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\RequestOptions;
// endpoints
use Psr\Http\Message\ResponseInterface;

class Client
{
    const API_ENDPOINT = 'https://api.copernica.com/v1';

    const HTTP_STATUS_NO_CONTENT = 204;

    /** @var string */
    protected $apiEndpoint = self::API_ENDPOINT;

    /** @var string */
    protected $apiToken;

    public function __construct(string $apiToken)
    {
        $this->httpClient = new HttpClient();
        $this->apiToken = $apiToken;
    }

    public function initializeEndpoints(): void
    {
//        $this->shipments = new Shipments($this);
    }

    /**
     * Set Api Token
     * @param string $apiToken
     */
    public function setApiToken(string $apiToken): void
    {
        $this->apiToken = trim($apiToken);
    }

    /**
     * Perform Http Call
     *
     * @param string $httpMethod
     * @param string $apiMethod
     * @param string|null $httpBody
     * @param array $arguments
     * @param array $requestHeaders
     * @return ResponseInterface
     * @throws SmtpeterException
     */
    public function performHttpCall(
        string $httpMethod,
        string $apiMethod,
        ?string $httpBody = null,
        array $arguments = [],
        array $requestHeaders = []
    ): ResponseInterface {
        if (empty($this->apiToken)) {
            throw new SmtpeterException(
                'You have not set an API Token, construct client with key or use setApiToken'
            );
        }

        // define headers
        $headers = collect([
            'Accept' => 'application/json'
        ])
            ->when($httpBody !== null, function ($collection) {
                return $collection->put('Content-Type', 'application/json');
            })
            ->merge($requestHeaders)
            ->all();

        $request = new Request(
            $httpMethod,
            $this->apiEndpoint . $apiMethod . "?" . http_build_query(['access_token' => $this->apiToken] + $arguments),
            $headers,
            $httpBody
        );

        try {
            $response = $this->httpClient->send($request, ['http_errors' => false]);
        } catch (GuzzleException $e) {
            throw new SmtpeterException($e->getMessage(), $e->getCode());
        }

        if (!$response) {
            throw new SmtpeterException('No API response received.');
        }

        return $response;
    }
}
