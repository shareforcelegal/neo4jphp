<?php

declare(strict_types=1);

namespace Shareforce\Neo4j\Transport;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

interface Transport
{
    /**
     * @return array<string, mixed>
     */
    public function decodeResponse(ResponseInterface $response): array;

    public function request(RequestInterface $request): ResponseInterface;
}
