<?php

declare(strict_types=1);

namespace Shareforce\Neo4j\Transport\Exception;

use Exception;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

final class InvalidResponseExceptionTest extends TestCase
{
    /**
     * @covers \Shareforce\Neo4j\Transport\Exception\InvalidResponseException::fromResponse
     */
    public function testFromResponse(): void
    {
        // Arrange
        $response = $this->getMockForAbstractClass(ResponseInterface::class);

        // Act
        $exception = InvalidResponseException::fromResponse($response);

        // Assert
        static::assertEquals('An invalid response was returned.', $exception->getMessage());
        static::assertNull($exception->getPrevious());
    }

    /**
     * @covers \Shareforce\Neo4j\Transport\Exception\InvalidResponseException::fromResponse
     */
    public function testFromResponseWithPrevious(): void
    {
        // Arrange
        $response = $this->getMockForAbstractClass(ResponseInterface::class);
        $previous = new Exception();

        // Act
        $exception = InvalidResponseException::fromResponse($response, $previous);

        // Assert
        static::assertEquals('An invalid response was returned.', $exception->getMessage());
        static::assertEquals($previous, $exception->getPrevious());
    }
}
