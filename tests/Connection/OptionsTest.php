<?php

declare(strict_types=1);

namespace Shareforce\Neo4j\Connection;

use PHPUnit\Framework\TestCase;

final class OptionsTest extends TestCase
{
    /**
     * @covers \Shareforce\Neo4j\Connection\Options::__construct
     * @covers \Shareforce\Neo4j\Connection\Options::create
     * @covers \Shareforce\Neo4j\Connection\Options::getHost
     */
    public function testCreateSetsHost(): void
    {
        // Arrange
        $config = [
            'host' => 'http://shareforce-neo4jphp:7474',
            'database' => 'shareforce',
            'username' => 'neo4j',
            'password' => 'shareforce',
        ];

        // Act
        $options = Options::create($config);

        // Assert
        static::assertEquals('http://shareforce-neo4jphp:7474', $options->getHost());
    }

    /**
     * @covers \Shareforce\Neo4j\Connection\Options::__construct
     * @covers \Shareforce\Neo4j\Connection\Options::create
     * @covers \Shareforce\Neo4j\Connection\Options::getHost
     */
    public function testCreateRemovesLastSlash(): void
    {
        // Arrange
        $config = [
            'host' => 'http://shareforce-neo4jphp:7474/',
            'database' => 'shareforce',
            'username' => 'neo4j',
            'password' => 'shareforce',
        ];

        // Act
        $options = Options::create($config);

        // Assert
        static::assertEquals('http://shareforce-neo4jphp:7474', $options->getHost());
    }

    /**
     * @covers \Shareforce\Neo4j\Connection\Options::__construct
     * @covers \Shareforce\Neo4j\Connection\Options::create
     * @covers \Shareforce\Neo4j\Connection\Options::getDatabase
     */
    public function testCreateSetsDatabase(): void
    {
        // Arrange
        $config = [
            'host' => 'http://shareforce-neo4jphp:7474/',
            'database' => 'shareforce',
            'username' => 'neo4j',
            'password' => 'shareforce',
        ];

        // Act
        $options = Options::create($config);

        // Assert
        static::assertEquals('shareforce', $options->getDatabase());
    }

    /**
     * @covers \Shareforce\Neo4j\Connection\Options::__construct
     * @covers \Shareforce\Neo4j\Connection\Options::create
     * @covers \Shareforce\Neo4j\Connection\Options::getUsername
     */
    public function testCreateSetsUsername(): void
    {
        // Arrange
        $config = [
            'host' => 'http://shareforce-neo4jphp:7474/',
            'database' => 'shareforce',
            'username' => 'neo4j',
            'password' => 'shareforce',
        ];

        // Act
        $options = Options::create($config);

        // Assert
        static::assertEquals('neo4j', $options->getUsername());
    }

    /**
     * @covers \Shareforce\Neo4j\Connection\Options::__construct
     * @covers \Shareforce\Neo4j\Connection\Options::create
     * @covers \Shareforce\Neo4j\Connection\Options::getPassword
     */
    public function testCreateSetsPassword(): void
    {
        // Arrange
        $config = [
            'host' => 'http://shareforce-neo4jphp:7474/',
            'database' => 'shareforce',
            'username' => 'neo4j',
            'password' => 'password',
        ];

        // Act
        $options = Options::create($config);

        // Assert
        static::assertEquals('password', $options->getPassword());
    }
}
