<?php

declare(strict_types=1);

namespace Shareforce\Neo4j\Exception;

use RuntimeException;

final class TransactionNotStartedException extends RuntimeException // phpcs:ignore
{
    public static function fromCommit(): TransactionNotStartedException
    {
        return new TransactionNotStartedException('Cannot commit a transaction which is not started.');
    }

    public static function fromKeepAlive(): TransactionNotStartedException
    {
        return new TransactionNotStartedException('Cannot keep transaction alive which is not started.');
    }

    public static function fromRollback(): TransactionNotStartedException
    {
        return new TransactionNotStartedException('Cannot rollback a transaction which is not started.');
    }
}
