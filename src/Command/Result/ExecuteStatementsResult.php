<?php

declare(strict_types=1);

namespace Shareforce\Neo4j\Command\Result;

final class ExecuteStatementsResult extends TransactionResult
{
    use ResultParser;

    /**
     * @param array<string, mixed> $body
     */
    public static function fromResponse(array $body): ExecuteStatementsResult
    {
        $result = new static();

        static::populateTransactionResult($result, $body);

        return $result;
    }
}
