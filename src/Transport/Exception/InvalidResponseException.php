<?php

declare(strict_types=1);

namespace Shareforce\Neo4j\Transport\Exception;

use Psr\Http\Message\ResponseInterface;
use RuntimeException;
use Throwable;

final class InvalidResponseException extends RuntimeException // phpcs:ignore
{
    public static function fromResponse(
        ResponseInterface $response,
        ?Throwable $previous = null
    ): InvalidResponseException {
        $msg = 'An invalid response was returned.';

        return new static($msg, 0, $previous);
    }
}
