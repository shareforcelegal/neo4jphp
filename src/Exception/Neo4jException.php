<?php

declare(strict_types=1);

namespace Shareforce\Neo4j\Exception;

use RuntimeException;
use Throwable;

final class Neo4jException extends RuntimeException // phpcs:ignore
{
    private int $neo4jCode;
    private string $neo4jMessage;

    private function __construct(string $message, int $code, ?Throwable $previous)
    {
        parent::__construct($message, $code, $previous);

        $this->neo4jCode = $code;
        $this->neo4jMessage = $message;
    }

    public function getNeo4jCode(): int
    {
        return $this->neo4jCode;
    }

    public function getNeo4jMessage(): string
    {
        return $this->neo4jMessage;
    }

    /**
     * @param array<int, array<string, mixed>> $errors
     */
    public static function fromResponseErrors(array $errors): Neo4jException
    {
        $exception = null;

        foreach ($errors as $error) {
            $exception = new Neo4jException($error['message'], $error['code'], $exception);
        }

        if ($exception === null) {
            $exception = new Neo4jException('Failed to parse errors!', 0, null);
        }

        return $exception;
    }
}
