<?php

declare(strict_types=1);

namespace Shareforce\Neo4j\Command\Result;

/**
 * @codeCoverageIgnore
 */
final class RollbackTransactionResult implements CommandResult
{
    /**
     * @param array<string, mixed> $body
     */
    public static function fromResponse(array $body): RollbackTransactionResult
    {
        return new RollbackTransactionResult();
    }
}
