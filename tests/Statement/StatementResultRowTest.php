<?php

declare(strict_types=1);

namespace Shareforce\Neo4j\Statement;

use PHPUnit\Framework\TestCase;

final class StatementResultRowTest extends TestCase
{
    /**
     * @covers \Shareforce\Neo4j\Statement\StatementResultRow::__construct
     * @covers \Shareforce\Neo4j\Statement\StatementResultRow::fromResponse
     * @covers \Shareforce\Neo4j\Statement\StatementResultRow::getRow
     */
    public function testGetRow(): void
    {
        // Arrange
        $statementResult = StatementResultRow::fromResponse([
            'row' => [],
            'meta' => [],
        ]);

        // Act
        $result = $statementResult->getRow();

        // Assert
        static::assertCount(0, $result);
    }

    /**
     * @covers \Shareforce\Neo4j\Statement\StatementResultRow::__construct
     * @covers \Shareforce\Neo4j\Statement\StatementResultRow::fromResponse
     * @covers \Shareforce\Neo4j\Statement\StatementResultRow::getRow
     */
    public function testGetRowWithData(): void
    {
        // Arrange
        $statementResult = StatementResultRow::fromResponse([
            'row' => [
                'value1',
                'value2',
            ],
            'meta' => [],
        ]);

        // Act
        $result = $statementResult->getRow();

        // Assert
        static::assertCount(2, $result);
    }

    /**
     * @covers \Shareforce\Neo4j\Statement\StatementResultRow::__construct
     * @covers \Shareforce\Neo4j\Statement\StatementResultRow::fromResponse
     * @covers \Shareforce\Neo4j\Statement\StatementResultRow::getMeta
     */
    public function testGetMetaWithData(): void
    {
        // Arrange
        $statementResult = StatementResultRow::fromResponse([
            'row' => [],
            'meta' => [
                'value1',
                'value2',
            ],
        ]);

        // Act
        $result = $statementResult->getMeta();

        // Assert
        static::assertCount(2, $result);
    }
}
