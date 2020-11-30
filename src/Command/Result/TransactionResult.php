<?php

declare(strict_types=1);

namespace Shareforce\Neo4j\Command\Result;

use DateTimeImmutable;
use Shareforce\Neo4j\Statement\StatementResultList;

use function array_key_exists;

abstract class TransactionResult implements CommandResult
{
    use ResultParser;

    protected string $commitUrl;
    protected ?string $transactionId = null;
    protected ?DateTimeImmutable $transactionExpires = null;
    protected StatementResultList $results;

    protected function __construct()
    {
        $this->commitUrl = '';
        $this->results = new StatementResultList();
    }

    /**
     * @param array<string, mixed> $body
     */
    protected static function populateTransactionResult(TransactionResult $result, array $body): void
    {
        if (array_key_exists('commit', $body)) {
            $result->commitUrl = $body['commit'];
        }

        $result->results = static::parseResults($body);
        $result->transactionExpires = static::parseTransactionExpirationDate($body);
        $result->transactionId = static::parseTransactionId($body);
    }

    public function getCommitUrl(): string
    {
        return $this->commitUrl;
    }

    public function getTransactionId(): ?string
    {
        return $this->transactionId;
    }

    public function getTransactionExpires(): ?DateTimeImmutable
    {
        return $this->transactionExpires;
    }

    public function getResults(): StatementResultList
    {
        return $this->results;
    }
}
