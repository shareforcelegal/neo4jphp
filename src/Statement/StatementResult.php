<?php

declare(strict_types=1);

namespace Shareforce\Neo4j\Statement;

use function array_map;

final class StatementResult
{
    /** @var array<int, string> */
    private array $columns;

    /** @var array<int, StatementResultRow> */
    private array $data;

    private function __construct()
    {
    }

    /**
     * @return array<int, string>
     */
    public function getColumns(): array
    {
        return $this->columns;
    }

    /**
     * @return array<int, StatementResultRow>
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param array<string, mixed> $response
     */
    public static function fromResponse(array $response): StatementResult
    {
        $result = new StatementResult();
        $result->columns = $response['columns'];
        $result->data = array_map(static function (array $data): StatementResultRow {
            return StatementResultRow::fromResponse($data);
        }, $response['data']);

        return $result;
    }
}
