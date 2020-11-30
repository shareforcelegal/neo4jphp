<?php

declare(strict_types=1);

namespace Shareforce\Neo4j\Connection;

use PHPUnit\Framework\TestCase;
use Shareforce\Neo4j\Command\BeginTransaction;
use Shareforce\Neo4j\Command\KeepTransactionAlive;
use Shareforce\Neo4j\Command\Result\BeginTransactionResult;
use Shareforce\Neo4j\Command\Result\CommitTransactionResult;
use Shareforce\Neo4j\Command\Result\ExecuteStatementsResult;
use Shareforce\Neo4j\Command\Result\KeepTransactionAliveResult;
use Shareforce\Neo4j\Command\RollbackTransaction;
use Shareforce\Neo4j\Command\Runner\CommandRunner;
use Shareforce\Neo4j\Exception\TransactionNotStartedException;
use Shareforce\Neo4j\Statement\Statement;

final class TransactionTest extends TestCase
{
    /**
     * @covers \Shareforce\Neo4j\Connection\Transaction::__construct
     * @covers \Shareforce\Neo4j\Connection\Transaction::getDatabase
     */
    public function testTransactionDatabaseIsSet(): void
    {
        // Arrange
        $database = 'shareforce';
        $commandRunner = $this->getMockForAbstractClass(CommandRunner::class);

        // Act
        $transaction = new Transaction($database, $commandRunner);

        // Assert
        static::assertEquals($database, $transaction->getDatabase());
    }

    /**
     * @covers \Shareforce\Neo4j\Connection\Transaction::__construct
     * @covers \Shareforce\Neo4j\Connection\Transaction::getTransactionId
     */
    public function testTransactionIdIsNullOnDefault(): void
    {
        // Arrange
        $database = 'shareforce';
        $commandRunner = $this->getMockForAbstractClass(CommandRunner::class);

        // Act
        $transaction = new Transaction($database, $commandRunner);

        // Assert
        static::assertNull($transaction->getTransactionId());
    }

    /**
     * @covers \Shareforce\Neo4j\Connection\Transaction::getTransactionId
     */
    public function testTransactionIdIsSet(): void
    {
        // Arrange
        $database = 'shareforce';
        $commandRunner = $this->getMockForAbstractClass(CommandRunner::class);
        $statement = new Statement('IGNORED');
        $transaction = new Transaction($database, $commandRunner);

        // Assert
        $commandRunner
            ->expects(static::once())
            ->method('run')
            ->with(static::equalTo(new BeginTransaction($database, [$statement])))
            ->willReturn(BeginTransactionResult::fromResponse(['commit' => 'http://ignored/tx/1/commit']));

        // Act
        $transaction->execute($statement);

        // Assert
        static::assertNotNull($transaction->getTransactionId());
    }

    /**
     * @covers \Shareforce\Neo4j\Connection\Transaction::begin
     * @covers \Shareforce\Neo4j\Connection\Transaction::execute
     */
    public function testExecuteWithSingleStatement(): void
    {
        // Arrange
        $database = 'shareforce';
        $commandRunner = $this->getMockForAbstractClass(CommandRunner::class);
        $statement = new Statement('IGNORED');
        $transaction = new Transaction($database, $commandRunner);

        // Assert
        $commandRunner
            ->expects(static::once())
            ->method('run')
            ->with(static::equalTo(new BeginTransaction($database, [$statement])))
            ->willReturn(BeginTransactionResult::fromResponse([]));

        // Act
        $transaction->execute($statement);
    }

    /**
     * @covers \Shareforce\Neo4j\Connection\Transaction::begin
     * @covers \Shareforce\Neo4j\Connection\Transaction::execute
     */
    public function testExecuteWithMultiStatement(): void
    {
        // Arrange
        $database = 'shareforce';
        $commandRunner = $this->getMockForAbstractClass(CommandRunner::class);
        $statement = new Statement('IGNORED');
        $transaction = new Transaction($database, $commandRunner);

        // Assert
        $commandRunner
            ->expects(static::exactly(2))
            ->method('run')
            ->willReturnOnConsecutiveCalls(
                BeginTransactionResult::fromResponse(['commit' => 'http://ignored/tx/1/commit']),
                ExecuteStatementsResult::fromResponse([])
            );

        // Act
        $transaction->execute($statement);
        $transaction->execute($statement);
    }

    /**
     * @covers \Shareforce\Neo4j\Connection\Transaction::commit
     */
    public function testCommit(): void
    {
        // Arrange
        $database = 'shareforce';
        $commandRunner = $this->getMockForAbstractClass(CommandRunner::class);
        $statement = new Statement('IGNORED');

        $transaction = new Transaction($database, $commandRunner);

        // Assert
        $commandRunner
            ->expects(static::exactly(2))
            ->method('run')
            ->willReturnOnConsecutiveCalls(
                BeginTransactionResult::fromResponse(['commit' => 'http://ignored/tx/1/commit']),
                CommitTransactionResult::fromResponse([])
            );

        // Act
        $transaction->execute($statement);
        $transaction->commit();
    }

    /**
     * @covers \Shareforce\Neo4j\Connection\Transaction::commit
     */
    public function testCommitUnstartedTransactionThrowsException(): void
    {
        // Arrange
        $database = 'shareforce';
        $commandRunner = $this->getMockForAbstractClass(CommandRunner::class);

        $transaction = new Transaction($database, $commandRunner);

        // Assert
        $this->expectException(TransactionNotStartedException::class);
        $this->expectExceptionMessage('Cannot commit a transaction which is not started.');

        // Act
        $transaction->commit();
    }

    /**
     * @covers \Shareforce\Neo4j\Connection\Transaction::keepAlive
     */
    public function testKeepAlive(): void
    {
        // Arrange
        $database = 'shareforce';
        $commandRunner = $this->getMockForAbstractClass(CommandRunner::class);
        $statement = new Statement('IGNORED');

        $transaction = new Transaction($database, $commandRunner);

        // Assert
        $commandRunner
            ->expects(static::exactly(2))
            ->method('run')
            ->withConsecutive(
                [new BeginTransaction($database, [$statement])],
                [new KeepTransactionAlive($database, '1')]
            )
            ->willReturnOnConsecutiveCalls(
                BeginTransactionResult::fromResponse(['commit' => 'http://ignored/tx/1/commit']),
                KeepTransactionAliveResult::fromResponse([])
            );

        // Act
        $transaction->execute($statement);
        $transaction->keepAlive();
    }

    /**
     * @covers \Shareforce\Neo4j\Connection\Transaction::keepAlive
     */
    public function testKeepAliveUnstartedTransactionThrowsException(): void
    {
        // Arrange
        $database = 'shareforce';
        $commandRunner = $this->getMockForAbstractClass(CommandRunner::class);

        $transaction = new Transaction($database, $commandRunner);

        // Assert
        $this->expectException(TransactionNotStartedException::class);
        $this->expectExceptionMessage('Cannot keep transaction alive which is not started.');

        // Act
        $transaction->keepAlive();
    }

    /**
     * @covers \Shareforce\Neo4j\Connection\Transaction::rollback
     */
    public function testRollback(): void
    {
        // Arrange
        $database = 'shareforce';
        $commandRunner = $this->getMockForAbstractClass(CommandRunner::class);
        $statement = new Statement('IGNORED');

        $transaction = new Transaction($database, $commandRunner);

        // Assert
        $commandRunner
            ->expects(static::exactly(2))
            ->method('run')
            ->withConsecutive(
                [new BeginTransaction($database, [$statement])],
                [new RollbackTransaction($database)]
            )
            ->willReturnOnConsecutiveCalls(
                BeginTransactionResult::fromResponse(['commit' => 'http://ignored/tx/1/commit']),
                CommitTransactionResult::fromResponse([])
            );

        // Act
        $transaction->execute($statement);
        $transaction->rollback();
    }

    /**
     * @covers \Shareforce\Neo4j\Connection\Transaction::rollback
     */
    public function testRollbackUnstartedTransactionThrowsException(): void
    {
        // Arrange
        $database = 'shareforce';
        $commandRunner = $this->getMockForAbstractClass(CommandRunner::class);

        $transaction = new Transaction($database, $commandRunner);

        // Assert
        $this->expectException(TransactionNotStartedException::class);
        $this->expectExceptionMessage('Cannot rollback a transaction which is not started.');

        // Act
        $transaction->rollback();
    }
}
