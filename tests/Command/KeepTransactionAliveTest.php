<?php

declare(strict_types=1);

namespace Shareforce\Neo4j\Command;

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Uri;
use PHPUnit\Framework\TestCase;
use Shareforce\Neo4j\Command\Result\KeepTransactionAliveResult;

final class KeepTransactionAliveTest extends TestCase
{
    /**
     * @covers \Shareforce\Neo4j\Command\KeepTransactionAlive::createResponse
     */
    public function testCreateResponseReturnsProperInstance(): void
    {
        // Arrange
        $command = new KeepTransactionAlive('database', 'transaction');

        // Act
        $result = $command->createResponse([]);

        // Assert
        static::assertInstanceOf(KeepTransactionAliveResult::class, $result);
    }

    /**
     * @covers \Shareforce\Neo4j\Command\KeepTransactionAlive::prepareRequest
     */
    public function testPrepareRequestSetsMethod(): void
    {
        // Arrange
        $request = new Request('GET', 'URI');
        $command = new KeepTransactionAlive('my-database', 'transaction');

        // Act
        $result = $command->prepareRequest($request);

        // Assert
        static::assertEquals('POST', $result->getMethod());
    }

    /**
     * @covers \Shareforce\Neo4j\Command\KeepTransactionAlive::prepareRequest
     */
    public function testPrepareRequestSetsContentTypeHeader(): void
    {
        // Arrange
        $request = new Request('GET', 'URI');
        $command = new KeepTransactionAlive('my-database', 'transaction');

        // Act
        $result = $command->prepareRequest($request);

        // Assert
        static::assertEquals('application/json', $result->getHeaderLine('Content-Type'));
    }

    /**
     * @covers \Shareforce\Neo4j\Command\KeepTransactionAlive::prepareRequest
     */
    public function testPrepareRequestSetsBody(): void
    {
        // Arrange
        $request = new Request('GET', 'URI');
        $command = new KeepTransactionAlive('my-database', 'transaction');

        // Act
        $result = $command->prepareRequest($request);

        // Assert
        static::assertEquals('{"statements":[]}', $result->getBody()->getContents());
    }

    /**
     * @covers \Shareforce\Neo4j\Command\KeepTransactionAlive::__construct
     * @covers \Shareforce\Neo4j\Command\KeepTransactionAlive::prepareRequestUri
     */
    public function testPrepareRequestUriSetsCorrectPath(): void
    {
        // Arrange
        $uri = new Uri();
        $command = new KeepTransactionAlive('my-database', 'transaction');

        // Act
        $result = $command->prepareRequestUri($uri);

        // Assert
        static::assertEquals('/db/my-database/tx/transaction', $result->getPath());
    }
}
