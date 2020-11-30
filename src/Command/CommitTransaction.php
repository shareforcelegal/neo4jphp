<?php

declare(strict_types=1);

namespace Shareforce\Neo4j\Command;

use GuzzleHttp\Psr7\Utils as Psr7Utils;
use GuzzleHttp\Utils as JsonUtils;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;
use Shareforce\Neo4j\Command\Result\CommandResult;
use Shareforce\Neo4j\Command\Result\CommitTransactionResult;
use Shareforce\Neo4j\Statement\Statement;

use function array_map;
use function sprintf;

final class CommitTransaction implements Command
{
    private string $database;

    private string $transactionId;

    /** @var array<int, Statement> */
    private array $statements;

    /**
     * @param array<int, Statement> $statements
     */
    public function __construct(string $database, string $transactionId, array $statements)
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
        return CommitTransactionResult::fromResponse($response);
    }

    public function prepareRequest(RequestInterface $request): RequestInterface
    {
        $stream = Psr7Utils::streamFor(JsonUtils::jsonEncode([
            'statements' => array_map(static function (Statement $statement): array {
                return $statement->toArray();
            }, $this->statements),
        ]));

        $request = $request->withMethod('POST');
        $request = $request->withHeader('Content-Type', 'application/json');
        $request = $request->withBody($stream);

        return $request;
    }

    public function prepareRequestUri(UriInterface $uri): UriInterface
    {
        return $uri->withPath(sprintf('/db/%s/tx/%s/commit', $this->database, $this->transactionId));
    }
}
