<?php

declare(strict_types=1);

namespace Shareforce\Neo4j\Command;

use GuzzleHttp\Psr7\Utils as Psr7Utils;
use GuzzleHttp\Utils as JsonUtils;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;
use Shareforce\Neo4j\Command\Result\BeginTransactionResult;
use Shareforce\Neo4j\Command\Result\CommandResult;
use Shareforce\Neo4j\Statement\Statement;

use function array_map;
use function count;
use function sprintf;

final class BeginTransaction implements Command
{
    private string $database;

    /** @var array<int, Statement> */
    private array $statements;

    /**
     * @param array<int, Statement> $statements
     */
    public function __construct(string $database, array $statements)
    {
        $this->database = $database;
        $this->statements = $statements;
    }

    /**
     * @param array<string, mixed> $response
     */
    public function createResponse(array $response): CommandResult
    {
        return BeginTransactionResult::fromResponse($response);
    }

    public function prepareRequest(RequestInterface $request): RequestInterface
    {
        $request = $request->withMethod('POST');

        if (count($this->statements) !== 0) {
            $stream = Psr7Utils::streamFor(JsonUtils::jsonEncode([
                'statements' => array_map(static function (Statement $statement): array {
                    return $statement->toArray();
                }, $this->statements),
            ]));

            $request = $request->withHeader('Content-Type', 'application/json');
            $request = $request->withBody($stream);
        }

        return $request;
    }

    public function prepareRequestUri(UriInterface $uri): UriInterface
    {
        return $uri->withPath(sprintf('/db/%s/tx', $this->database));
    }
}
