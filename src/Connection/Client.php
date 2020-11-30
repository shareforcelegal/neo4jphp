<?php

declare(strict_types=1);

namespace Shareforce\Neo4j\Connection;

use Shareforce\Neo4j\Command\ExecuteStatements;
use Shareforce\Neo4j\Command\Result\ExecuteStatementsResult;
use Shareforce\Neo4j\Command\Result\RootDiscoveryResult;
use Shareforce\Neo4j\Command\RootDiscovery;
use Shareforce\Neo4j\Command\Runner\CommandRunner;
use Shareforce\Neo4j\Statement\Statement;
use Shareforce\Neo4j\Statement\StatementResultList;

use function assert;

final class Client
{
    private Options $options;
    private CommandRunner $commandRunner;

    public function __construct(Options $options, CommandRunner $commandRunner)
    {
        $this->options = $options;
        $this->commandRunner = $commandRunner;
    }

    public function beginTransaction(): Transaction
    {
        return new Transaction($this->options->getDatabase(), $this->commandRunner);
    }

    /**
     * @param array<string, mixed> $parameters
     */
    public function executeQuery(string $query, array $parameters = []): StatementResultList
    {
        $statement = new Statement($query, $parameters);

        return $this->executeStatement($statement);
    }

    public function executeStatement(Statement $statement): StatementResultList
    {
        $result = $this->commandRunner->run(new ExecuteStatements($this->options->getDatabase(), null, [$statement]));
        assert($result instanceof ExecuteStatementsResult);

        return $result->getResults();
    }

    public function getRootDiscovery(): RootDiscoveryResult
    {
        $result = $this->commandRunner->run(new RootDiscovery());
        assert($result instanceof RootDiscoveryResult);

        return $result;
    }
}
