<?php

declare(strict_types=1);

namespace Shareforce\Neo4j\Statement;

use PHPUnit\Framework\TestCase;

final class StatementTest extends TestCase
{
    /**
     * @covers \Shareforce\Neo4j\Statement\Statement::__construct
     * @covers \Shareforce\Neo4j\Statement\Statement::getStatement
     */
    public function testGetStatement(): void
    {
        // Arrange
        $statement = new Statement('my statement');

        // Act
        $result = $statement->getStatement();

        // Assert
        static::assertEquals('my statement', $result);
    }

    /**
     * @covers \Shareforce\Neo4j\Statement\Statement::__construct
     * @covers \Shareforce\Neo4j\Statement\Statement::getParameters
     */
    public function testConstructorSetsDefaultParameters(): void
    {
        // Arrange
        $statement = new Statement('my statement');

        // Act
        $result = $statement->getParameters();

        // Assert
        static::assertCount(0, $result);
    }

    /**
     * @covers \Shareforce\Neo4j\Statement\Statement::setParameter
     */
    public function testSetParameter(): void
    {
        // Arrange
        $statement = new Statement('my statement');

        // Act
        $statement->setParameter('param', 'value');

        // Assert
        static::assertArrayHasKey('param', $statement->getParameters());
    }

    /**
     * @covers \Shareforce\Neo4j\Statement\Statement::__construct
     * @covers \Shareforce\Neo4j\Statement\Statement::getIncludeStats
     */
    public function testIncludeStatsIsFalseByDefault(): void
    {
        // Arrange
        $statement = new Statement('my statement');

        // Act
        $result = $statement->getIncludeStats();

        // Assert
        static::assertFalse($result);
    }

    /**
     * @covers \Shareforce\Neo4j\Statement\Statement::setIncludeStats
     */
    public function testSetIncludeStats(): void
    {
        // Arrange
        $statement = new Statement('my statement');

        // Act
        $statement->setIncludeStats(true);

        // Assert
        static::assertTrue($statement->getIncludeStats());
    }

    /**
     * @covers \Shareforce\Neo4j\Statement\Statement::__construct
     * @covers \Shareforce\Neo4j\Statement\Statement::getResultDataContents
     */
    public function testDefaultResultDataContents(): void
    {
        // Arrange
        $statement = new Statement('my statement');

        // Act
        $result = $statement->getResultDataContents();

        // Assert
        static::assertEquals(['row'], $result);
    }

    /**
     * @covers \Shareforce\Neo4j\Statement\Statement::getResultDataContents
     * @covers \Shareforce\Neo4j\Statement\Statement::setResultDataContents
     */
    public function testSettingResultDataContents(): void
    {
        // Arrange
        $statement = new Statement('my statement');

        // Act
        $statement->setResultDataContents(['column']);

        // Assert
        static::assertEquals(['column'], $statement->getResultDataContents());
    }

    /**
     * @covers \Shareforce\Neo4j\Statement\Statement::toArray
     */
    public function testToArray(): void
    {
        // Arrange
        $statement = new Statement('my statement');

        // Act
        $result = $statement->toArray();

        // Assert
        static::assertEquals([
            'statement' => 'my statement',
            'includeStats' => false,
            'resultDataContents' => ['row'],
        ], $result);
    }

    /**
     * @covers \Shareforce\Neo4j\Statement\Statement::toArray
     */
    public function testToArrayWithParameter(): void
    {
        // Arrange
        $statement = new Statement('my statement');
        $statement->setParameter('param', 'value');

        // Act
        $result = $statement->toArray();

        // Assert
        static::assertEquals([
            'statement' => 'my statement',
            'includeStats' => false,
            'resultDataContents' => ['row'],
            'parameters' => ['param' => 'value'],
        ], $result);
    }
}
