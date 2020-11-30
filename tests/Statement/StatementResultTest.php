<?php

declare(strict_types=1);

namespace Shareforce\Neo4j\Statement;

use PHPUnit\Framework\TestCase;

final class StatementResultTest extends TestCase
{
    /**
     * @covers \Shareforce\Neo4j\Statement\StatementResult::__construct
     * @covers \Shareforce\Neo4j\Statement\StatementResult::fromResponse
     * @covers \Shareforce\Neo4j\Statement\StatementResult::getData
     */
    public function testGetData(): void
    {
        // Arrange
        $statementResult = StatementResult::fromResponse([
            'columns' => [],
            'data' => [],
        ]);

        // Act
        $result = $statementResult->getData();

        // Assert
        static::assertCount(0, $result);
    }

    /**
     * @covers \Shareforce\Neo4j\Statement\StatementResult::__construct
     * @covers \Shareforce\Neo4j\Statement\StatementResult::fromResponse
     * @covers \Shareforce\Neo4j\Statement\StatementResult::getData
     */
    public function testGetDataWithRows(): void
    {
        // Arrange
        $statementResult = StatementResult::fromResponse([
            'columns' => [],
            'data' => [
                [
                    'row' => [],
                    'meta' => [],
                ],
            ],
        ]);

        // Act
        $result = $statementResult->getData();

        // Assert
        static::assertCount(1, $result);
    }

    /**
     * @covers \Shareforce\Neo4j\Statement\StatementResult::__construct
     * @covers \Shareforce\Neo4j\Statement\StatementResult::fromResponse
     * @covers \Shareforce\Neo4j\Statement\StatementResult::getColumns
     */
    public function testGetColumns(): void
    {
        // Arrange
        $statementResult = StatementResult::fromResponse([
            'columns' => [],
            'data' => [],
        ]);

        // Act
        $result = $statementResult->getColumns();

        // Assert
        static::assertCount(0, $result);
    }

    /**
     * @covers \Shareforce\Neo4j\Statement\StatementResult::__construct
     * @covers \Shareforce\Neo4j\Statement\StatementResult::fromResponse
     * @covers \Shareforce\Neo4j\Statement\StatementResult::getColumns
     */
    public function testGetColumnsWithColumns(): void
    {
        // Arrange
        $statementResult = StatementResult::fromResponse([
            'columns' => [
                'column1',
                'column2',
                'column3',
            ],
            'data' => [],
        ]);

        // Act
        $result = $statementResult->getColumns();

        // Assert
        static::assertCount(3, $result);
    }
}
