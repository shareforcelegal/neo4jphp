<?php

declare(strict_types=1);

namespace Shareforce\Neo4j\Connection;

use Shareforce\Neo4j\Command\BeginTransaction;
use Shareforce\Neo4j\Command\CommitTransaction;
use Shareforce\Neo4j\Command\ExecuteStatements;
use Shareforce\Neo4j\Command\KeepTransactionAlive;
use Shareforce\Neo4j\Command\Result\BeginTransactionResult;
use Shareforce\Neo4j\Command\Result\CommitTransactionResult;
use Shareforce\Neo4j\Command\Result\ExecuteStatementsResult;
use Shareforce\Neo4j\Command\Result\TransactionResult;
use Shareforce\Neo4j\Command\RollbackTransaction;
use Shareforce\Neo4j\Command\Runner\CommandRunner;
use Shareforce\Neo4j\Exception\TransactionAlreadyStartedException;
use Shareforce\Neo4j\Exception\TransactionNotStartedException;
use Shareforce\Neo4j\Statement\Statement;

use function assert;

final class Transaction
{
    private string $database;
    private CommandRunner $commandRunner;
    private ?string $transactionId;

    public function __construct(string $database, CommandRunner $commandRunner)
    {
        $this->database = $database;
        $this->commandRunner = $commandRunner;
        $this->transactionId = null;
    }

    public function getDatabase(): string
    {
        return $this->database;
    }

    public function getTransactionId(): ?string
    {
        return $this->transactionId;
    }

    public function execute(Statement $statement): TransactionResult
    {
        if ($this->transactionId === null) {
            return $this->begin([$statement]);
        }

        $response = $this->commandRunner->run(new ExecuteStatements(
            $this->database,
            $this->transactionId,
            [$statement]
        ));
        assert($response instanceof ExecuteStatementsResult);

        return $response;
    }

    /**
     * @param array<int, Statement> $statements
     */
    private function begin(array $statements): TransactionResult
    {
        // @codeCoverageIgnoreStart
        // Following statement is untestable at this point since the code does not allow it to happen. Yet I want
        // this code here for possible future errors
        if ($this->transactionId !== null) {
            throw TransactionAlreadyStartedException::create();
        }

        // @codeCoverageIgnoreEnd

        $response = $this->commandRunner->run(new BeginTransaction($this->database, $statements));
        assert($response instanceof BeginTransactionResult);

        $this->transactionId = $response->getTransactionId();

        return $response;
    }

    /**
     * @param array<int, Statement> $statements
     */
    public function commit(array $statements = []): TransactionResult
    {
        if ($this->transactionId === null) {
            throw TransactionNotStartedException::fromCommit();
        }

        $response = $this->commandRunner->run(new CommitTransaction(
            $this->database,
            $this->transactionId,
            $statements
        ));
        assert($response instanceof CommitTransactionResult);

        return $response;
    }

    public function keepAlive(): void
    {
        if ($this->transactionId === null) {
            throw TransactionNotStartedException::fromKeepAlive();
        }

        $this->commandRunner->run(new KeepTransactionAlive(
            $this->database,
            $this->transactionId
        ));
    }

    public function rollback(): void
    {
        if ($this->transactionId === null) {
            throw TransactionNotStartedException::fromRollback();
        }

        $this->commandRunner->run(new RollbackTransaction($this->database));
    }
}
