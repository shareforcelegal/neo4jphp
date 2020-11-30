<?php

declare(strict_types=1);

namespace Shareforce\Neo4j\Exception;

use PHPUnit\Framework\TestCase;

final class TransactionNotStartedTest extends TestCase
{
    /**
     * @covers \Shareforce\Neo4j\Exception\TransactionNotStartedException::fromCommit
     */
    public function testFromCommit(): void
    {
        // Arrange

        // Act
        $exception = TransactionNotStartedException::fromCommit();

        // Assert
        static::assertEquals('Cannot commit a transaction which is not started.', $exception->getMessage());
    }

    /**
     * @covers \Shareforce\Neo4j\Exception\TransactionNotStartedException::fromKeepAlive
     */
    public function testFromKeepAlive(): void
    {
        // Arrange

        // Act
        $exception = TransactionNotStartedException::fromKeepAlive();

        // Assert
        static::assertEquals('Cannot keep transaction alive which is not started.', $exception->getMessage());
    }

    /**
     * @covers \Shareforce\Neo4j\Exception\TransactionNotStartedException::fromRollback
     */
    public function testFromRollback(): void
    {
        // Arrange

        // Act
        $exception = TransactionNotStartedException::fromRollback();

        // Assert
        static::assertEquals('Cannot rollback a transaction which is not started.', $exception->getMessage());
    }
}
