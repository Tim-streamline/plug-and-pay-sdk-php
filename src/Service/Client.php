<?php

declare(strict_types=1);

namespace PlugAndPay\Sdk\Service;

use GuzzleHttp\Client as GuzzleClient;
use PlugAndPay\Sdk\Contract\ClientDeleteInterface;
use PlugAndPay\Sdk\Contract\ClientGetInterface;
use PlugAndPay\Sdk\Contract\ClientPatchInterface;
use PlugAndPay\Sdk\Contract\ClientPostInterface;
use PlugAndPay\Sdk\Entity\Response;
use Psr\Http\Message\ResponseInterface;

class Client implements ClientPatchInterface, ClientPostInterface, ClientGetInterface, ClientDeleteInterface
{
    private const METHOD_DELETE = 'DELETE';
    private const METHOD_GET = 'GET';
    private const METHOD_PATCH = 'PATCH';
    private const METHOD_POST = 'POST';

    /**
     * @var \GuzzleHttp\Client
     */
    private GuzzleClient $guzzleClient;

    public function __construct(string $baseUrl, string $secretToken, GuzzleClient $guzzleClient = null)
    {
        $this->guzzleClient = $guzzleClient ?? new GuzzleClient([
                'base_uri' => $baseUrl,
                'headers'  => [
                    'Accept'        => 'application/json',
                    'Authorization' => "Bearer $secretToken",
                ],
                'timeout'  => 25,
            ]);
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function delete(string $path): Response
    {
        $response = $this->guzzleClient->request(self::METHOD_DELETE, $path);

        return new Response($response->getStatusCode());
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \JsonException
     */
    public function get(string $path): Response
    {
        $response = $this->guzzleClient->request(self::METHOD_GET, $path);

        return $this->fromGuzzleResponse($response);
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \JsonException
     */
    public function patch(string $path, array $body): Response
    {
        $options  = [
            'json' => $body,
        ];
        $response = $this->guzzleClient->request(self::METHOD_PATCH, $path, $options);

        return $this->fromGuzzleResponse($response);
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \JsonException
     */
    public function post(string $path, array $body): Response
    {
        $options  = [
            'json' => $body,
        ];
        $response = $this->guzzleClient->request(self::METHOD_POST, $path, $options);

        return $this->fromGuzzleResponse($response);
    }

    private function fromGuzzleResponse(ResponseInterface $response): Response
    {
        return new Response($response->getStatusCode(), json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR));
    }
}