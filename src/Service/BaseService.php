<?php

namespace OxidSolutionCatalysts\PayPalApi\Service;

use GuzzleHttp\Exception\GuzzleException;
use OxidSolutionCatalysts\PayPal\Service\Logger;
use OxidSolutionCatalysts\PayPal\Traits\ServiceContainer;
use OxidSolutionCatalysts\PayPalApi\Client;
use OxidSolutionCatalysts\PayPalApi\Exception\ApiException;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Psr7\Query;

class BaseService
{
    use ServiceContainer;

    /** @var Client */
    public $client;

    /**
     * @param $client Client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param string $method
     * @param string $path
     * @param array $params
     * @param array<string,string> $headers
     * @param null|string $body
     * @return ResponseInterface
     * @throws ApiException
     */
    protected function send($method, $path, $params = [], $headers = [], $body = null): ResponseInterface
    {
        /** @var Logger $logger */
        $logger = $this->getServiceFromContainer(Logger::class);

        $params = array_filter($params);
        if ($params) {
            $q = Query::build($params);
            $path = "$path?$q";
        }
        $fullPath = $this->basePath . $path;

        if ($body) {
            $headers['PayPal-Request-Id'] = md5($path . serialize($body) . $this->client->getActionHash());
        }

        $request = $this->client->createRequest($method, $fullPath, $headers, $body);
        $logger->log('debug', 'PayPal SEND path ' . $path);
        $logger->log('debug', 'PayPal SEND request ' . $request->getBody());
        $logger->log('debug', 'PayPal SEND headers ' . serialize($request->getHeaders()));
        try {
            $response = $this->client->send($request);
        } catch (GuzzleException $exception) {
            $logger->log('error', $exception->getMessage(), [$exception]);
            throw new ApiException($exception);
        }
        return $response;
    }
}
