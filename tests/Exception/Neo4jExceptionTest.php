<?php

declare(strict_types=1);

namespace Shareforce\Neo4j\Exception;

use PHPUnit\Framework\TestCase;

final class Neo4jExceptionTest extends TestCase
{
    /**
     * @covers \Shareforce\Neo4j\Exception\Neo4jException::fromResponseErrors
     */
    public function testFromResponseErrorsWithEmptyErrors(): void
    {
        // Arrange
        $errors = [];

        // Act
        $exception = Neo4jException::fromResponseErrors($errors);

        // Assert
        static::assertEquals('Failed to parse errors!', $exception->getMessage());
    }

    /**
     * @covers \Shareforce\Neo4j\Exception\Neo4jException::fromResponseErrors
     */
    public function testFromResponseErrorsWithSingleError(): void
    {
        // Arrange
        $errors = [
            [
                'code' => 42,
                'message' => 'message',
            ],
        ];

        // Act
        $exception = Neo4jException::fromResponseErrors($errors);

        // Assert
        static::assertEquals('message', $exception->getMessage());
        static::assertEquals(42, $exception->getCode());
        static::assertNull($exception->getPrevious());
    }

    /**
     * @covers \Shareforce\Neo4j\Exception\Neo4jException::fromResponseErrors
     */
    public function testFromResponseErrorsWithMultipleErrors(): void
    {
        // Arrange
        $errors = [
            [
                'code' => 42,
                'message' => 'message',
            ],
            [
                'code' => 1337,
                'message' => 'message2',
            ],
        ];

        // Act
        $exception = Neo4jException::fromResponseErrors($errors);

        // Assert
        static::assertEquals('message2', $exception->getMessage());
        static::assertEquals(1337, $exception->getCode());
        static::assertNotNull($exception->getPrevious());
    }

    /**
     * @covers \Shareforce\Neo4j\Exception\Neo4jException::__construct
     * @covers \Shareforce\Neo4j\Exception\Neo4jException::getNeo4jCode
     */
    public function testGetNeo4jCode(): void
    {
        // Arrange
        $errors = [
            [
                'code' => 42,
                'message' => 'message',
            ],
        ];

        $exception = Neo4jException::fromResponseErrors($errors);

        // Act
        $result = $exception->getNeo4jCode();

        // Assert
        static::assertEquals(42, $result);
    }

    /**
     * @covers \Shareforce\Neo4j\Exception\Neo4jException::__construct
     * @covers \Shareforce\Neo4j\Exception\Neo4jException::getNeo4jMessage
     */
    public function testGetNeo4jMessage(): void
    {
        // Arrange
        $errors = [
            [
                'code' => 42,
                'message' => 'message',
            ],
        ];

        $exception = Neo4jException::fromResponseErrors($errors);

        // Act
        $result = $exception->getNeo4jMessage();

        // Assert
        static::assertEquals('message', $result);
    }
}
