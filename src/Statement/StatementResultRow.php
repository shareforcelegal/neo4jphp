<?php

declare(strict_types=1);

namespace Shareforce\Neo4j\Statement;

final class StatementResultRow
{
    /** @var array<int, mixed> */
    private array $row;

    /** @var array<int, mixed> */
    private array $meta;

    private function __construct()
    {
    }

    /**
     * @return array<int, mixed>
     */
    public function getRow(): array
    {
        return $this->row;
    }

    /**
     * @return array<int, mixed>
     */
    public function getMeta(): array
    {
        return $this->meta;
    }

    /**
     * @param array<string, mixed> $response
     */
    public static function fromResponse(array $response): StatementResultRow
    {
        $result = new StatementResultRow();
        $result->row = $response['row'];
        $result->meta = $response['meta'];

        return $result;
    }
}
