<?php

declare(strict_types=1);

namespace Shareforce\Neo4j\Command;

use GuzzleHttp\Psr7\Utils as Psr7Utils;
use GuzzleHttp\Utils as JsonUtils;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;
use Shareforce\Neo4j\Command\Result\CommandResult;
use Shareforce\Neo4j\Command\Result\ExecuteStatementsResult;
use Shareforce\Neo4j\Statement\Statement;

use function array_map;
use function sprintf;

final class ExecuteStatements implements Command
{
    private string $database;
    private ?string $transactionId = null;

    /** @var array<int, Statement> */
    private array $statements;

    /**
     * @param array<int, Statement> $statements
     */
    public function __construct(string $database, ?string $transactionId, array $statements = [])
    {
        $this->database = $database;
        $this->transactionId = $transactionId;
        $this->statements = $statements;
    }

    /**
     * @param array<string, mixed> $response
     */
    public function createResponse(array $response): CommandResult
    {
        return ExecuteStatementsResult::fromResponse($response);
    }

    public function prepareRequest(RequestInterface $request): RequestInterface
    {
        $stream = Psr7Utils::streamFor(JsonUtils::jsonEncode([
            'statements' => array_map(static function (Statement $statement): array {
                return $statement->toArray();
            }, $this->statements),
        ]));

        return $request
            ->withMethod('POST')
            ->withHeader('Content-Type', 'application/json')
            ->withBody($stream);
    }

    public function prepareRequestUri(UriInterface $uri): UriInterface
    {
        if ($this->transactionId === null) {
            return $uri->withPath(sprintf('/db/%s/tx/commit', $this->database));
        }

        return $uri->withPath(sprintf('/db/%s/tx/%s', $this->database, $this->transactionId));
    }
}
