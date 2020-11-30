<?php

declare(strict_types=1);

namespace Shareforce\Neo4j\Transport;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Utils;
use PHPUnit\Framework\TestCase;
use Shareforce\Neo4j\Transport\Exception\InvalidResponseException;

final class GuzzleTransportTest extends TestCase
{
    /**
     * @covers \Shareforce\Neo4j\Transport\GuzzleTransport::__construct
     * @covers \Shareforce\Neo4j\Transport\GuzzleTransport::createRequest
     */
    public function testCreateRequest(): void
    {
        // Arrange
        $client = new Client();
        $transport = new GuzzleTransport($client);

        // Act
        $result = $transport->createRequest('GET', '/some/uri');

        // Assert
        static::assertEquals('GET', $result->getMethod());
        static::assertEquals('/some/uri', (string)$result->getUri());
    }

    /**
     * @covers \Shareforce\Neo4j\Transport\GuzzleTransport::__construct
     * @covers \Shareforce\Neo4j\Transport\GuzzleTransport::createUri
     */
    public function testCreateUri(): void
    {
        // Arrange
        $client = new Client();
        $transport = new GuzzleTransport($client);

        // Act
        $result = $transport->createUri('/some/uri');

        // Assert
        static::assertEquals('/some/uri', (string)$result);
    }

    /**
     * @covers \Shareforce\Neo4j\Transport\GuzzleTransport::__construct
     * @covers \Shareforce\Neo4j\Transport\GuzzleTransport::decodeResponse
     */
    public function testDecodeResponse(): void
    {
        // Arrange
        $client = new Client();
        $transport = new GuzzleTransport($client);
        $response = new Response(200, [], Utils::streamFor('{"test": "test"}'));

        // Act
        $result = $transport->decodeResponse($response);

        // Assert
        static::assertArrayHasKey('test', $result);
    }

    /**
     * @covers \Shareforce\Neo4j\Transport\GuzzleTransport::__construct
     * @covers \Shareforce\Neo4j\Transport\GuzzleTransport::decodeResponse
     */
    public function testDecodeResponseWithInvalidResponseThrowsException(): void
    {
        // Arrange
        $client = new Client();
        $transport = new GuzzleTransport($client);
        $response = new Response(200, [], Utils::streamFor('1337'));

        // Assert
        $this->expectException(InvalidResponseException::class);
        $this->expectExceptionMessage('An invalid response was returned.');

        // Act
        $transport->decodeResponse($response);
    }

    /**
     * @covers \Shareforce\Neo4j\Transport\GuzzleTransport::__construct
     * @covers \Shareforce\Neo4j\Transport\GuzzleTransport::request
     */
    public function testRequest(): void
    {
        // Assert
        $client = $this->getMockForAbstractClass(ClientInterface::class);
        $client->expects(static::once())->method('send');

        // Arrange
        $transport = new GuzzleTransport($client);
        $request = $transport->createRequest('GET', '/some/uri');

        // Act
        $transport->request($request);
    }
}
