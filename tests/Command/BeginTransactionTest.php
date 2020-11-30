<?php

declare(strict_types=1);

namespace Shareforce\Neo4j\Command;

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Uri;
use PHPUnit\Framework\TestCase;
use Shareforce\Neo4j\Command\Result\BeginTransactionResult;
use Shareforce\Neo4j\Statement\Statement;

final class BeginTransactionTest extends TestCase
{
    /**
     * @covers \Shareforce\Neo4j\Command\BeginTransaction::createResponse
     */
    public function testCreateResponseReturnsProperInstance(): void
    {
        // Arrange
        $command = new BeginTransaction('my-database', []);

        // Act
        $result = $command->createResponse([]);

        // Assert
        static::assertInstanceOf(BeginTransactionResult::class, $result);
    }

    /**
     * @covers \Shareforce\Neo4j\Command\BeginTransaction::prepareRequest
     */
    public function testPrepareRequestSetsMethod(): void
    {
        // Arrange
        $request = new Request('GET', 'URI');
        $command = new BeginTransaction('my-database', []);

        // Act
        $result = $command->prepareRequest($request);

        // Assert
        static::assertEquals('POST', $result->getMethod());
    }

    /**
     * @covers \Shareforce\Neo4j\Command\BeginTransaction::prepareRequest
     */
    public function testPrepareRequestSetsContentTypeHeader(): void
    {
        // Arrange
        $request = new Request('GET', 'URI');
        $command = new BeginTransaction('my-database', []);

        // Act
        $result = $command->prepareRequest($request);

        // Assert
        static::assertEquals('', $result->getHeaderLine('Content-Type'));
    }

    /**
     * @covers \Shareforce\Neo4j\Command\BeginTransaction::prepareRequest
     */
    public function testPrepareRequestWithStatementsSetsContentTypeHeader(): void
    {
        // Arrange
        $request = new Request('GET', 'URI');
        $command = new BeginTransaction('my-database', [
            new Statement('statement'),
        ]);

        // Act
        $result = $command->prepareRequest($request);

        // Assert
        static::assertEquals('application/json', $result->getHeaderLine('Content-Type'));
    }

    /**
     * @covers \Shareforce\Neo4j\Command\BeginTransaction::prepareRequest
     */
    public function testPrepareRequestSetsBody(): void
    {
        // Arrange
        $request = new Request('GET', 'URI');
        $command = new BeginTransaction('my-database', []);

        // Act
        $result = $command->prepareRequest($request);

        // Assert
        static::assertEquals('', $result->getBody()->getContents());
    }

    /**
     * @covers \Shareforce\Neo4j\Command\BeginTransaction::prepareRequest
     */
    public function testPrepareRequestSetsBodyWithStatements(): void
    {
        // Arrange
        $request = new Request('GET', 'URI');
        $command = new BeginTransaction('my-database', [
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
     * @covers \Shareforce\Neo4j\Command\BeginTransaction::__construct
     * @covers \Shareforce\Neo4j\Command\BeginTransaction::prepareRequestUri
     */
    public function testPrepareRequestUriSetsCorrectPath(): void
    {
        // Arrange
        $uri = new Uri();
        $command = new BeginTransaction('my-database', []);

        // Act
        $result = $command->prepareRequestUri($uri);

        // Assert
        static::assertEquals('/db/my-database/tx', $result->getPath());
    }
}
