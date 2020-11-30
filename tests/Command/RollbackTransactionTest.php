<?php

declare(strict_types=1);

namespace Shareforce\Neo4j\Command;

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Uri;
use PHPUnit\Framework\TestCase;
use Shareforce\Neo4j\Command\Result\RollbackTransactionResult;

final class RollbackTransactionTest extends TestCase
{
    /**
     * @covers \Shareforce\Neo4j\Command\RollbackTransaction::createResponse
     */
    public function testCreateResponseReturnsProperInstance(): void
    {
        // Arrange
        $command = new RollbackTransaction('database');

        // Act
        $result = $command->createResponse([]);

        // Assert
        static::assertInstanceOf(RollbackTransactionResult::class, $result);
    }

    /**
     * @covers \Shareforce\Neo4j\Command\RollbackTransaction::prepareRequest
     */
    public function testPrepareRequestSetsMethod(): void
    {
        // Arrange
        $request = new Request('GET', 'URI');
        $command = new RollbackTransaction('my-database');

        // Act
        $result = $command->prepareRequest($request);

        // Assert
        static::assertEquals('POST', $result->getMethod());
    }

    /**
     * @covers \Shareforce\Neo4j\Command\RollbackTransaction::prepareRequest
     */
    public function testPrepareRequestSetsContentTypeHeader(): void
    {
        // Arrange
        $request = new Request('GET', 'URI');
        $command = new RollbackTransaction('my-database');

        // Act
        $result = $command->prepareRequest($request);

        // Assert
        static::assertEquals('application/json', $result->getHeaderLine('Content-Type'));
    }

    /**
     * @covers \Shareforce\Neo4j\Command\RollbackTransaction::prepareRequest
     */
    public function testPrepareRequestSetsBody(): void
    {
        // Arrange
        $request = new Request('GET', 'URI');
        $command = new RollbackTransaction('my-database');

        // Act
        $result = $command->prepareRequest($request);

        // Assert
        static::assertEquals('{"statements":[]}', $result->getBody()->getContents());
    }

    /**
     * @covers \Shareforce\Neo4j\Command\RollbackTransaction::__construct
     * @covers \Shareforce\Neo4j\Command\RollbackTransaction::prepareRequestUri
     */
    public function testPrepareRequestUriSetsCorrectPath(): void
    {
        // Arrange
        $uri = new Uri();
        $command = new RollbackTransaction('my-database');

        // Act
        $result = $command->prepareRequestUri($uri);

        // Assert
        static::assertEquals('/db/my-database/tx/commit', $result->getPath());
    }
}
