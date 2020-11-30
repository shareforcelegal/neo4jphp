<?php

declare(strict_types=1);

namespace Shareforce\Neo4j\Statement;

use PHPUnit\Framework\TestCase;

final class StatementResultListTest extends TestCase
{
    /**
     * @covers \Shareforce\Neo4j\Statement\StatementResultList::__construct
     */
    public function testEmptyByDefault(): void
    {
        // Arrange
        $list = new StatementResultList();

        // Act
        // ...

        // Assert
        static::assertCount(0, $list);
    }

    /**
     * @covers \Shareforce\Neo4j\Statement\StatementResultList::__construct
     * @covers \Shareforce\Neo4j\Statement\StatementResultList::add
     */
    public function testAdd(): void
    {
        // Arrange
        $list = new StatementResultList();

        // Act
        $list->add(StatementResult::fromResponse([
            'columns' => [],
            'data' => [],
        ]));

        // Assert
        static::assertCount(1, $list);
    }

    /**
     * @covers \Shareforce\Neo4j\Statement\StatementResultList::__construct
     * @covers \Shareforce\Neo4j\Statement\StatementResultList::count
     */
    public function testCount(): void
    {
        // Arrange
        $list = new StatementResultList();
        $list->add(StatementResult::fromResponse([
            'columns' => [],
            'data' => [],
        ]));

        // Act
        $result = $list->count();

        // Assert
        static::assertEquals(1, $result);
    }

    /**
     * @covers \Shareforce\Neo4j\Statement\StatementResultList::__construct
     * @covers \Shareforce\Neo4j\Statement\StatementResultList::getIterator
     */
    public function testGetIteratorContainsData(): void
    {
        // Arrange
        $list = new StatementResultList();
        $list->add(StatementResult::fromResponse([
            'columns' => [],
            'data' => [],
        ]));

        // Act
        $result = $list->getIterator();

        // Assert
        static::assertTrue($result->valid());
    }
}
