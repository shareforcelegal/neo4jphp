<?php

declare(strict_types=1);

namespace Shareforce\Neo4j\Command;

use GuzzleHttp\Psr7\Utils as Psr7Utils;
use GuzzleHttp\Utils as JsonUtils;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;
use Shareforce\Neo4j\Command\Result\CommandResult;
use Shareforce\Neo4j\Command\Result\RollbackTransactionResult;

use function sprintf;

final class RollbackTransaction implements Command
{
    private string $database;

    public function __construct(string $database)
    {
        $this->database = $database;
    }

    /**
     * @param array<string, mixed> $response
     */
    public function createResponse(array $response): CommandResult
    {
        return RollbackTransactionResult::fromResponse($response);
    }

    public function prepareRequest(RequestInterface $request): RequestInterface
    {
        $stream = Psr7Utils::streamFor(JsonUtils::jsonEncode([
            'statements' => [],
        ]));

        $request = $request->withMethod('POST');
        $request = $request->withHeader('Content-Type', 'application/json');
        $request = $request->withBody($stream);

        return $request;
    }

    public function prepareRequestUri(UriInterface $uri): UriInterface
    {
        return $uri->withPath(sprintf('/db/%s/tx/commit', $this->database));
    }
}
