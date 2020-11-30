<?php

declare(strict_types=1);

namespace Shareforce\Neo4j\Command\Result;

use DateTimeImmutable;
use Shareforce\Neo4j\Statement\StatementResult;
use Shareforce\Neo4j\Statement\StatementResultList;

use function array_key_exists;
use function array_map;
use function count;
use function explode;

trait ResultParser
{
    /**
     * @param array<string, mixed> $body
     */
    protected static function parseResults(array $body): StatementResultList
    {
        $result = new StatementResultList();

        if (array_key_exists('results', $body)) {
            array_map(static function (array $response) use ($result): void {
                $result->add(StatementResult::fromResponse($response));
            }, $body['results']);
        }

        return $result;
    }

    /**
     * @param array<string, mixed> $body
     */
    protected static function parseTransactionExpirationDate(array $body): ?DateTimeImmutable
    {
        if (!array_key_exists('transaction', $body)) {
            return null;
        }

        if (!array_key_exists('expires', $body['transaction'])) {
            return null;
        }

        $result = DateTimeImmutable::createFromFormat(
            'D, j M Y H:i:s e',
            $body['transaction']['expires']
        );

        if ($result === false) {
            return null;
        }

        return $result;
    }

    /**
     * @param array<string, mixed> $body
     */
    protected static function parseTransactionId(array $body): ?string
    {
        if (!array_key_exists('commit', $body)) {
            return null;
        }

        $splitted = explode('/', $body['commit']);

        return $splitted[count($splitted) - 2];
    }
}
