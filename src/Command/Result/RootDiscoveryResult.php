<?php

declare(strict_types=1);

namespace Shareforce\Neo4j\Command\Result;

use Webmozart\Assert\Assert;

final class RootDiscoveryResult implements CommandResult
{
    private string $neo4jVersion;
    private string $neo4jEdition;

    private function __construct()
    {
    }

    public function getNeo4jVersion(): string
    {
        return $this->neo4jVersion;
    }

    public function getNeo4jEdition(): string
    {
        return $this->neo4jEdition;
    }

    /**
     * @param array<string, mixed> $response
     */
    public static function fromResponse(array $response): RootDiscoveryResult
    {
        Assert::keyExists($response, 'neo4j_version');
        Assert::keyExists($response, 'neo4j_edition');

        $result = new static();
        $result->neo4jVersion = $response['neo4j_version'];
        $result->neo4jEdition = $response['neo4j_edition'];

        return $result;
    }
}
