<?php

declare(strict_types=1);

namespace Shareforce\Neo4j\Exception;

use PHPUnit\Framework\TestCase;

final class TransactionAlreadyStartedTest extends TestCase
{
    /**
     * @covers \Shareforce\Neo4j\Exception\TransactionAlreadyStartedException::create
     */
    public function testCreate(): void
    {
        // Arrange

        // Act
        $exception = TransactionAlreadyStartedException::create();

        // Assert
        static::assertEquals('Cannot start a transaction which is already started.', $exception->getMessage());
    }
}
