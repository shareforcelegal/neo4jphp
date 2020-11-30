<?php

declare(strict_types=1);

namespace Shareforce\Neo4j\Exception;

use RuntimeException;

final class TransactionAlreadyStartedException extends RuntimeException // phpcs:ignore
{
    public static function create(): TransactionAlreadyStartedException
    {
        return new static('Cannot start a transaction which is already started.');
    }
}
