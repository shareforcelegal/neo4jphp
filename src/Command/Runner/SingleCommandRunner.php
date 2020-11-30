<?php

declare(strict_types=1);

namespace Shareforce\Neo4j\Command\Runner;

use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriFactoryInterface;
use Shareforce\Neo4j\Command\Command;
use Shareforce\Neo4j\Command\Result\CommandResult;
use Shareforce\Neo4j\Connection\Options;
use Shareforce\Neo4j\Exception\Neo4jException;
use Shareforce\Neo4j\Transport\Transport;

use function array_key_exists;
use function base64_encode;
use function count;
use function sprintf;

final class SingleCommandRunner implements CommandRunner
{
    private Options $options;
    private Transport $transport;
    private UriFactoryInterface $uriFactory;
    private RequestFactoryInterface $requestFactory;

    public function __construct(
        Options $options,
        Transport $transport,
        UriFactoryInterface $uriFactory,
        RequestFactoryInterface $requestFactory
    ) {
        $this->options = $options;
        $this->transport = $transport;
        $this->uriFactory = $uriFactory;
        $this->requestFactory = $requestFactory;
    }

    public function run(Command $command): CommandResult
    {
        $uri = $this->uriFactory->createUri($this->options->getHost());
        $uri = $command->prepareRequestUri($uri);

        $request = $this->requestFactory->createRequest('GET', $uri);
        $request = $request->withHeader('Accept', 'application/json;charset=UTF-8');
        $request = $this->prepareAuthorization($request);
        //$request = $request->withHeader('X-Stream', 'true');
        $request = $command->prepareRequest($request);

        $response = $this->transport->request($request);

        $body = $this->transport->decodeResponse($response);

        if (array_key_exists('errors', $body) && count($body['errors']) !== 0) {
            throw Neo4jException::fromResponseErrors($body['errors']);
        }

        return $command->createResponse($body);
    }

    private function prepareAuthorization(RequestInterface $request): RequestInterface
    {
        $username = $this->options->getUsername();
        $password = $this->options->getPassword();

        if ($username === '') {
            return $request;
        }

        $basicAuth = sprintf('%s:%s', $username, $password);

        return $request->withHeader('Authorization', 'Basic ' . base64_encode($basicAuth));
    }
}
