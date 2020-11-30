<?php

declare(strict_types=1);

namespace Shareforce\Neo4j\Statement;

use function count;

final class Statement
{
    private string $statement;

    /** @var array<string, mixed> */
    private array $parameters;

    private bool $includeStats;

    /** @var array<int, string> */
    private array $resultDataContents;

    /**
     * @param array<string, mixed> $parameters
     */
    public function __construct(string $statement, array $parameters = [])
    {
        $this->statement = $statement;
        $this->parameters = $parameters;
        $this->includeStats = false;
        $this->resultDataContents = ['row'];
    }

    public function getStatement(): string
    {
        return $this->statement;
    }

    /**
     * @return array<string, mixed>
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * @param mixed $value
     */
    public function setParameter(string $name, $value): void
    {
        $this->parameters[$name] = $value;
    }

    public function getIncludeStats(): bool
    {
        return $this->includeStats;
    }

    public function setIncludeStats(bool $includeStats): void
    {
        $this->includeStats = $includeStats;
    }

    /**
     * @return array<int, string>
     */
    public function getResultDataContents(): array
    {
        return $this->resultDataContents;
    }

    /**
     * @param array<int, string> $resultDataContents
     */
    public function setResultDataContents(array $resultDataContents): void
    {
        $this->resultDataContents = $resultDataContents;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $result = [
            'statement' => $this->getStatement(),
            'includeStats' => $this->getIncludeStats(),
            'resultDataContents' => $this->getResultDataContents(),
        ];

        $parameters = $this->getParameters();

        if (count($parameters) !== 0) {
            $result['parameters'] = $parameters;
        }

        return $result;
    }
}
