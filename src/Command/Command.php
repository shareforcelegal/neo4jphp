<?php

declare(strict_types=1);

namespace Shareforce\Neo4j\Command;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;
use Shareforce\Neo4j\Command\Result\CommandResult;

interface Command
{
    /**
     * @param array<string, mixed> $response
     */
    public function createResponse(array $response): CommandResult;

    public function prepareRequest(RequestInterface $request): RequestInterface;

    public function prepareRequestUri(UriInterface $uri): UriInterface;
}
