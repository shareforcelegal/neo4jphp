<?php

declare(strict_types=1);

namespace Shareforce\Neo4j\Command\Runner;

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Uri;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;
use Shareforce\Neo4j\Command\RootDiscovery;
use Shareforce\Neo4j\Connection\Options;
use Shareforce\Neo4j\Exception\Neo4jException;
use Shareforce\Neo4j\Transport\Transport;

final class SingleCommandRunnerTest extends TestCase
{
    /**
     * @covers \Shareforce\Neo4j\Command\Runner\SingleCommandRunner::__construct
     * @covers \Shareforce\Neo4j\Command\Runner\SingleCommandRunner::run
     * @covers \Shareforce\Neo4j\Command\Runner\SingleCommandRunner::prepareAuthorization
     */
    public function testRunWithUsername(): void
    {
        // Arrange
        $options = Options::create([
            'host' => '',
            'database' => '',
            'username' => 'username',
            'password' => 'password',
        ]);
        $transport = $this->getMockForAbstractClass(Transport::class);
        $uriFactory = $this->getMockForAbstractClass(UriFactoryInterface::class);
        $requestFactory = $this->getMockForAbstractClass(RequestFactoryInterface::class);

        $uri = new Uri();
        $request = new Request('GET', $uri);

        $expectedRequest = new Request('GET', $uri);
        $expectedRequest = $expectedRequest->withAddedHeader('Accept', 'application/json;charset=UTF-8');
        $expectedRequest = $expectedRequest->withAddedHeader('Authorization', 'Basic dXNlcm5hbWU6cGFzc3dvcmQ=');

        $runner = new SingleCommandRunner($options, $transport, $uriFactory, $requestFactory);
        $command = new RootDiscovery();

        // Assert
        $uriFactory->expects(static::once())->method('createUri')->willReturn($uri);
        $requestFactory->expects(static::once())->method('createRequest')->willReturn($request);
        $transport
            ->expects(static::once())
            ->method('request')
            ->with(static::equalTo($expectedRequest));
        $transport
            ->expects(static::once())
            ->method('decodeResponse')
            ->willReturn([
                'neo4j_version' => 'version',
                'neo4j_edition' => 'edition',
            ]);

        // Act
        $runner->run($command);
    }

    /**
     * @covers \Shareforce\Neo4j\Command\Runner\SingleCommandRunner::__construct
     * @covers \Shareforce\Neo4j\Command\Runner\SingleCommandRunner::run
     * @covers \Shareforce\Neo4j\Command\Runner\SingleCommandRunner::prepareAuthorization
     */
    public function testRunWithoutUsername(): void
    {
        // Arrange
        $options = Options::create([
            'host' => '',
            'database' => '',
            'username' => '',
            'password' => '',
        ]);
        $transport = $this->getMockForAbstractClass(Transport::class);
        $uriFactory = $this->getMockForAbstractClass(UriFactoryInterface::class);
        $requestFactory = $this->getMockForAbstractClass(RequestFactoryInterface::class);

        $uri = new Uri();
        $request = new Request('GET', $uri);

        $expectedRequest = new Request('GET', $uri);
        $expectedRequest = $expectedRequest->withAddedHeader('Accept', 'application/json;charset=UTF-8');

        $runner = new SingleCommandRunner($options, $transport, $uriFactory, $requestFactory);
        $command = new RootDiscovery();

        // Assert
        $uriFactory->expects(static::once())->method('createUri')->willReturn($uri);
        $requestFactory->expects(static::once())->method('createRequest')->willReturn($request);
        $transport
            ->expects(static::once())
            ->method('request')
            ->with(static::equalTo($expectedRequest));
        $transport
            ->expects(static::once())
            ->method('decodeResponse')
            ->willReturn([
                'neo4j_version' => 'version',
                'neo4j_edition' => 'edition',
            ]);

        // Act
        $runner->run($command);
    }

    /**
     * @covers \Shareforce\Neo4j\Command\Runner\SingleCommandRunner::__construct
     * @covers \Shareforce\Neo4j\Command\Runner\SingleCommandRunner::run
     * @covers \Shareforce\Neo4j\Command\Runner\SingleCommandRunner::prepareAuthorization
     */
    public function testRunWithErrors(): void
    {
        // Arrange
        $options = Options::create([
            'host' => '',
            'database' => '',
            'username' => '',
            'password' => '',
        ]);
        $transport = $this->getMockForAbstractClass(Transport::class);
        $uriFactory = $this->getMockForAbstractClass(UriFactoryInterface::class);
        $requestFactory = $this->getMockForAbstractClass(RequestFactoryInterface::class);

        $uri = new Uri();
        $request = new Request('GET', $uri);

        $expectedRequest = new Request('GET', $uri);
        $expectedRequest = $expectedRequest->withAddedHeader('Accept', 'application/json;charset=UTF-8');

        $runner = new SingleCommandRunner($options, $transport, $uriFactory, $requestFactory);
        $command = new RootDiscovery();

        // Assert
        $this->expectException(Neo4jException::class);

        $uriFactory->expects(static::once())->method('createUri')->willReturn($uri);
        $requestFactory->expects(static::once())->method('createRequest')->willReturn($request);
        $transport
            ->expects(static::once())
            ->method('request')
            ->with(static::equalTo($expectedRequest));
        $transport
            ->expects(static::once())
            ->method('decodeResponse')
            ->willReturn([
                'errors' => [
                    [
                        'code' => 0,
                        'message' => 'message',
                    ],
                ],
            ]);

        // Act
        $runner->run($command);
    }
}
