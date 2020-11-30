<?php

declare(strict_types=1);

namespace Shareforce\Neo4j\Command;

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Uri;
use PHPUnit\Framework\TestCase;
use Shareforce\Neo4j\Command\Result\CommitTransactionResult;
use Shareforce\Neo4j\Statement\Statement;

final class CommitTransactionTest extends TestCase
{
    /**
     * @covers \Shareforce\Neo4j\Command\CommitTransaction::createResponse
     */
    public function testCreateResponseReturnsProperInstance(): void
    {
        // Arrange
        $command = new CommitTransaction('database', 'transaction', []);

        // Act
        $result = $command->createResponse([]);

        // Assert
        static::assertInstanceOf(CommitTransactionResult::class, $result);
    }

    /**
     * @covers \Shareforce\Neo4j\Command\CommitTransaction::prepareRequest
     */
    public function testPrepareRequestSetsMethod(): void
    {
        // Arrange
        $request = new Request('GET', 'URI');
        $command = new CommitTransaction('my-database', 'transaction', []);

        // Act
        $result = $command->prepareRequest($request);

        // Assert
        static::assertEquals('POST', $result->getMethod());
    }

    /**
     * @covers \Shareforce\Neo4j\Command\CommitTransaction::prepareRequest
     */
    public function testPrepareRequestSetsContentTypeHeader(): void
    {
        // Arrange
        $request = new Request('GET', 'URI');
        $command = new CommitTransaction('my-database', 'transaction', []);

        // Act
        $result = $command->prepareRequest($request);

        // Assert
        static::assertEquals('application/json', $result->getHeaderLine('Content-Type'));
    }

    /**
     * @covers \Shareforce\Neo4j\Command\CommitTransaction::prepareRequest
     */
    public function testPrepareRequestSetsBody(): void
    {
        // Arrange
        $request = new Request('GET', 'URI');
        $command = new CommitTransaction('my-database', 'transaction', []);

        // Act
        $result = $command->prepareRequest($request);

        // Assert
        static::assertEquals('{"statements":[]}', $result->getBody()->getContents());
    }

    /**
     * @covers \Shareforce\Neo4j\Command\CommitTransaction::prepareRequest
     */
    public function testPrepareRequestSetsBodyWithStatements(): void
    {
        // Arrange
        $request = new Request('GET', 'URI');
        $command = new CommitTransaction('my-database', 'transaction', [
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
     * @covers \Shareforce\Neo4j\Command\CommitTransaction::__construct
     * @covers \Shareforce\Neo4j\Command\CommitTransaction::prepareRequestUri
     */
    public function testPrepareRequestUriSetsCorrectPath(): void
    {
        // Arrange
        $uri = new Uri();
        $command = new CommitTransaction('my-database', 'transaction', []);

        // Act
        $result = $command->prepareRequestUri($uri);

        // Assert
        static::assertEquals('/db/my-database/tx/transaction/commit', $result->getPath());
    }
}
