<?php

declare(strict_types=1);

namespace Shareforce\Neo4j\Command;

use GuzzleHttp\Psr7\Uri;
use PHPUnit\Framework\TestCase;
use Shareforce\Neo4j\Command\Result\RootDiscoveryResult;

final class RootDiscoveryTest extends TestCase
{
    /**
     * @covers \Shareforce\Neo4j\Command\RootDiscovery::createResponse
     */
    public function testCreateResponseReturnsProperInstance(): void
    {
        // Arrange
        $command = new RootDiscovery();

        // Act
        $result = $command->createResponse([
            'neo4j_version' => 'version',
            'neo4j_edition' => 'edition',
        ]);

        // Assert
        static::assertInstanceOf(RootDiscoveryResult::class, $result);
    }

    /**
     * @covers \Shareforce\Neo4j\Command\RootDiscovery::prepareRequestUri
     */
    public function testPrepareRequestUriSetsCorrectPath(): void
    {
        // Arrange
        $uri = new Uri();
        $command = new RootDiscovery();

        // Act
        $result = $command->prepareRequestUri($uri);

        // Assert
        static::assertEquals('/', $result->getPath());
    }
}
