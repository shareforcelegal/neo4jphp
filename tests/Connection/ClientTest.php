<?php

declare(strict_types=1);

namespace Shareforce\Neo4j\Connection;

use PHPUnit\Framework\TestCase;
use Shareforce\Neo4j\Command\Result\ExecuteStatementsResult;
use Shareforce\Neo4j\Command\Result\RootDiscoveryResult;
use Shareforce\Neo4j\Command\Runner\CommandRunner;

final class ClientTest extends TestCase
{
    /**
     * @covers \Shareforce\Neo4j\Connection\Client::__construct
     * @covers \Shareforce\Neo4j\Connection\Client::beginTransaction
     */
    public function testBeginTransaction(): void
    {
        // Arrange
        $options = Options::create([
            'host' => 'host',
            'database' => 'database',
            'username' => 'username',
            'password' => 'password',
        ]);
        $commandRunner = $this->getMockForAbstractClass(CommandRunner::class);
        $client = new Client($options, $commandRunner);

        // Act
        $transaction = $client->beginTransaction();

        // Assert
        static::assertEquals('database', $transaction->getDatabase());
    }

    /**
     * @covers \Shareforce\Neo4j\Connection\Client::__construct
     * @covers \Shareforce\Neo4j\Connection\Client::executeQuery
     */
    public function testExecuteQuery(): void
    {
        // Arrange
        $options = Options::create([
            'host' => 'host',
            'database' => 'database',
            'username' => 'username',
            'password' => 'password',
        ]);

        $commandRunner = $this->getMockForAbstractClass(CommandRunner::class);
        $commandRunner->expects(static::once())->method('run')->willReturn(ExecuteStatementsResult::fromResponse([]));

        $client = new Client($options, $commandRunner);

        // Act
        $result = $client->executeQuery('IGNORED');

        // Assert
        static::assertCount(0, $result);
    }

    /**
     * @covers \Shareforce\Neo4j\Connection\Client::__construct
     * @covers \Shareforce\Neo4j\Connection\Client::executeQuery
     * @covers \Shareforce\Neo4j\Connection\Client::executeStatement
     */
    public function testExecuteStatement(): void
    {
        // Arrange
        $options = Options::create([
            'host' => 'host',
            'database' => 'database',
            'username' => 'username',
            'password' => 'password',
        ]);

        $commandRunner = $this->getMockForAbstractClass(CommandRunner::class);
        $commandRunner->expects(static::once())->method('run')->willReturn(ExecuteStatementsResult::fromResponse([]));

        $client = new Client($options, $commandRunner);

        // Act
        $result = $client->executeQuery('IGNORED');

        // Assert
        static::assertCount(0, $result);
    }

    /**
     * @covers \Shareforce\Neo4j\Connection\Client::__construct
     * @covers \Shareforce\Neo4j\Connection\Client::getRootDiscovery
     */
    public function testGetRootDiscovery(): void
    {
        // Arrange
        $options = Options::create([
            'host' => 'host',
            'database' => 'database',
            'username' => 'username',
            'password' => 'password',
        ]);

        $commandRunner = $this->getMockForAbstractClass(CommandRunner::class);
        $commandRunner->expects(static::once())->method('run')->willReturn(RootDiscoveryResult::fromResponse([
            'neo4j_version' => '42',
            'neo4j_edition' => 'THE ANSWER TO LIFE, THE UNIVERSE AND EVERYTHING',
        ]));

        $client = new Client($options, $commandRunner);

        // Act
        $result = $client->getRootDiscovery();

        // Assert
        static::assertEquals('42', $result->getNeo4jVersion());
        static::assertEquals('THE ANSWER TO LIFE, THE UNIVERSE AND EVERYTHING', $result->getNeo4jEdition());
    }
}
