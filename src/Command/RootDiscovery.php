<?php

declare(strict_types=1);

namespace Shareforce\Neo4j\Command;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;
use Shareforce\Neo4j\Command\Result\CommandResult;
use Shareforce\Neo4j\Command\Result\RootDiscoveryResult;

final class RootDiscovery implements Command
{
    /**
     * @param array<string, mixed> $response
     */
    public function createResponse(array $response): CommandResult
    {
        return RootDiscoveryResult::fromResponse($response);
    }

    /**
     * @codeCoverageIgnore
     */
    public function prepareRequest(RequestInterface $request): RequestInterface
    {
        return $request;
    }

    public function prepareRequestUri(UriInterface $uri): UriInterface
    {
        return $uri->withPath('/');
    }
}
