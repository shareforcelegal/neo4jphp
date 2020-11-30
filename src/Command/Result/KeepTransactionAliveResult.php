<?php

declare(strict_types=1);

namespace Shareforce\Neo4j\Command\Result;

final class KeepTransactionAliveResult extends TransactionResult
{
    use ResultParser;

    /**
     * @param array<string, mixed> $body
     */
    public static function fromResponse(array $body): KeepTransactionAliveResult
    {
        $result = new static();

        static::populateTransactionResult($result, $body);

        return $result;
    }
}
