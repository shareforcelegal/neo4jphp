<?php

declare(strict_types=1);

namespace Shareforce\Neo4j\Command;

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Uri;
use PHPUnit\Framework\TestCase;
use Shareforce\Neo4j\Command\Result\ExecuteStatementsResult;
use Shareforce\Neo4j\Statement\Statement;

final class ExecuteStatementsTest extends TestCase
{
    /**
     * @covers \Shareforce\Neo4j\Command\ExecuteStatements::createResponse
     */
    public function testCreateResponseReturnsProperInstance(): void
    {
        // Arrange
        $command = new ExecuteStatements('database', 'transaction');

        // Act
        $result = $command->createResponse([]);

        // Assert
        static::assertInstanceOf(ExecuteStatementsResult::class, $result);
    }

    /**
     * @covers \Shareforce\Neo4j\Command\ExecuteStatements::prepareRequest
     */
    public function testPrepareRequestSetsMethod(): void
    {
        // Arrange
        $request = new Request('GET', 'URI');
        $command = new ExecuteStatements('my-database', 'transaction');

        // Act
        $result = $command->prepareRequest($request);

        // Assert
        static::assertEquals('POST', $result->getMethod());
    }

    /**
     * @covers \Shareforce\Neo4j\Command\ExecuteStatements::prepareRequest
     */
    public function testPrepareRequestSetsContentTypeHeader(): void
    {
        // Arrange
        $request = new Request('GET', 'URI');
        $command = new ExecuteStatements('my-database', 'transaction');

        // Act
        $result = $command->prepareRequest($request);

        // Assert
        static::assertEquals('application/json', $result->getHeaderLine('Content-Type'));
    }

    /**
     * @covers \Shareforce\Neo4j\Command\ExecuteStatements::prepareRequest
     */
    public function testPrepareRequestSetsBody(): void
    {
        // Arrange
        $request = new Request('GET', 'URI');
        $command = new ExecuteStatements('my-database', 'transaction');

        // Act
        $result = $command->prepareRequest($request);

        // Assert
        static::assertEquals('{"statements":[]}', $result->getBody()->getContents());
    }

    /**
     * @covers \Shareforce\Neo4j\Command\ExecuteStatements::prepareRequest
     */
    public function testPrepareRequestSetsBodyWithStatements(): void
    {
        // Arrange
        $request = new Request('GET', 'URI');
        $command = new ExecuteStatements('my-database', 'transaction', [
            new Statement('statement'),
        ]);

        // Act
        $result = $command->prepareRequest($request);

        // Assert
        static::assertEquals(
            '{"statements":[{"statement":"statement","includeStats":false,"resultDataContents":["row"]}]}',
            $result->getBody()->getContents()
        );
    }

    /**
     * @covers \Shareforce\Neo4j\Command\ExecuteStatements::__construct
     * @covers \Shareforce\Neo4j\Command\ExecuteStatements::prepareRequestUri
     */
    public function testPrepareRequestUriSetsCorrectPath(): void
    {
        // Arrange
        $uri = new Uri();
        $command = new ExecuteStatements('my-database', 'transaction');

        // Act
        $result = $command->prepareRequestUri($uri);

        // Assert
        static::assertEquals('/db/my-database/tx/transaction', $result->getPath());
    }

    /**
     * @covers \Shareforce\Neo4j\Command\ExecuteStatements::__construct
     * @covers \Shareforce\Neo4j\Command\ExecuteStatements::prepareRequestUri
     */
    public function testPrepareRequestUriSetsCorrectPathWithoutTransaction(): void
    {
        // Arrange
        $uri = new Uri();
        $command = new ExecuteStatements('my-database', null);

        // Act
        $result = $command->prepareRequestUri($uri);

        // Assert
        static::assertEquals('/db/my-database/tx/commit', $result->getPath());
    }
}
