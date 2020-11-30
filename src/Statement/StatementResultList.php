<?php

declare(strict_types=1);

namespace Shareforce\Neo4j\Statement;

use ArrayIterator;
use Countable;
use Iterator;
use IteratorAggregate;

use function count;

/**
 * @implements IteratorAggregate<StatementResult>
 */
final class StatementResultList implements Countable, IteratorAggregate
{
    /** @var array<int, StatementResult> */
    private array $items;

    public function __construct()
    {
        $this->items = [];
    }

    public function add(StatementResult $item): void
    {
        $this->items[] = $item;
    }

    /**
     * @return int
     */
    public function count() // phpcs:ignore
    {
        return count($this->items);
    }

    /**
     * @return Iterator<StatementResult>
     */
    public function getIterator() // phpcs:ignore
    {
        return new ArrayIterator($this->items);
    }
}
