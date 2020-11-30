<?php

declare(strict_types=1);

namespace Shareforce\Neo4j\Command\Result;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class RootDiscoveryResultTest extends TestCase
{
    /**
     * @covers \Shareforce\Neo4j\Command\Result\RootDiscoveryResult::__construct
     * @covers \Shareforce\Neo4j\Command\Result\RootDiscoveryResult::fromResponse
     */
    public function testNoEditionThrowsException(): void
    {
        // Arrange
        $data = ['neo4j_version' => 'version'];

        // Assert
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Expected the key "neo4j_edition" to exist.');

        // Act
        RootDiscoveryResult::fromResponse($data);
    }

    /**
     * @covers \Shareforce\Neo4j\Command\Result\RootDiscoveryResult::__construct
     * @covers \Shareforce\Neo4j\Command\Result\RootDiscoveryResult::fromResponse
     */
    public function testNoVersionThrowsException(): void
    {
        // Arrange
        $data = ['neo4j_edition' => 'edition'];

        // Assert
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Expected the key "neo4j_version" to exist.');

        // Act
        RootDiscoveryResult::fromResponse($data);
    }

    /**
     * @covers \Shareforce\Neo4j\Command\Result\RootDiscoveryResult::__construct
     * @covers \Shareforce\Neo4j\Command\Result\RootDiscoveryResult::fromResponse
     * @covers \Shareforce\Neo4j\Command\Result\RootDiscoveryResult::getNeo4jEdition
     */
    public function testEditionIsPopulated(): void
    {
        // Arrange
        $data = [
            'neo4j_edition' => 'edition',
            'neo4j_version' => 'version',
        ];

        // Act
        $result = RootDiscoveryResult::fromResponse($data);

        // Assert
        static::assertEquals('edition', $result->getNeo4jEdition());
    }

    /**
     * @covers \Shareforce\Neo4j\Command\Result\RootDiscoveryResult::__construct
     * @covers \Shareforce\Neo4j\Command\Result\RootDiscoveryResult::fromResponse
     * @covers \Shareforce\Neo4j\Command\Result\RootDiscoveryResult::getNeo4jVersion
     */
    public function testVersionIsPopulated(): void
    {
        // Arrange
        $data = [
            'neo4j_edition' => 'edition',
            'neo4j_version' => 'version',
        ];

        // Act
        $result = RootDiscoveryResult::fromResponse($data);

        // Assert
        static::assertEquals('version', $result->getNeo4jVersion());
    }
}
