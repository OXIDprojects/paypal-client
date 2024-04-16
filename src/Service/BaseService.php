<?php

namespace OxidSolutionCatalysts\PayPalApi\Service;

use GuzzleHttp\Exception\GuzzleException;
use OxidSolutionCatalysts\PayPalApi\Client;
use OxidSolutionCatalysts\PayPalApi\Exception\ApiException;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Psr7\Query;

class BaseService
{
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
        $params = array_filter($params);
        if ($params) {
            $q = Query::build($params);
            $path = "$path?$q";
        }
        $fullPath = $this->basePath . $path;

        if ($body) {
            $headers['PayPal-Request-Id'] = md5(serialize($body));
        }

        $request = $this->client->createRequest($method, $fullPath, $headers, $body);
        try {
            $response = $this->client->send($request);
        } catch (GuzzleException $e) {
            throw new ApiException($e);
        }
        return $response;
    }
}
