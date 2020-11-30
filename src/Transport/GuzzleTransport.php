<?php

declare(strict_types=1);

namespace Shareforce\Neo4j\Transport;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Uri;
use GuzzleHttp\Utils;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriFactoryInterface;
use Psr\Http\Message\UriInterface;
use Shareforce\Neo4j\Transport\Exception\InvalidResponseException;

use function is_array;

final class GuzzleTransport implements Transport, RequestFactoryInterface, UriFactoryInterface
{
    private ClientInterface $httpClient;

    public function __construct(ClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @inheritDoc
     */
    public function createRequest(string $method, $uri): RequestInterface
    {
        return new Request($method, $uri);
    }

    public function createUri(string $uri = ''): UriInterface
    {
        return new Uri($uri);
    }

    /**
     * @return array<string, mixed>
     */
    public function decodeResponse(ResponseInterface $response): array
    {
        $responseBody = $response->getBody()->getContents();

        $body = Utils::jsonDecode($responseBody, true);

        if (!is_array($body)) {
            throw InvalidResponseException::fromResponse($response);
        }

        return $body;
    }

    public function request(RequestInterface $request): ResponseInterface
    {
        return $this->httpClient->send($request);
    }
}
