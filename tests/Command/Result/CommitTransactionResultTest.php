<?php

declare(strict_types=1);

namespace Shareforce\Neo4j\Command\Result;

use PHPUnit\Framework\TestCase;

use function date;

final class CommitTransactionResultTest extends TestCase
{
    /**
     * @covers \Shareforce\Neo4j\Command\Result\CommitTransactionResult::fromResponse
     * @covers \Shareforce\Neo4j\Command\Result\TransactionResult::__construct
     * @covers \Shareforce\Neo4j\Command\Result\TransactionResult::populateTransactionResult
     * @covers \Shareforce\Neo4j\Command\Result\TransactionResult::getCommitUrl
     */
    public function testCommitUrlIsPopulated(): void
    {
        // Arrange
        $data = ['commit' => 'commit-url'];

        // Act
        $result = CommitTransactionResult::fromResponse($data);

        // Assert
        static::assertEquals('commit-url', $result->getCommitUrl());
    }

    /**
     * @covers \Shareforce\Neo4j\Command\Result\CommitTransactionResult::fromResponse
     * @covers \Shareforce\Neo4j\Command\Result\TransactionResult::__construct
     * @covers \Shareforce\Neo4j\Command\Result\TransactionResult::populateTransactionResult
     * @covers \Shareforce\Neo4j\Command\Result\TransactionResult::getResults
     * @covers \Shareforce\Neo4j\Command\Result\ResultParser::parseResults
     */
    public function testResultIsPopulated(): void
    {
        // Arrange
        $data = [
            'results' => [],
        ];

        // Act
        $result = CommitTransactionResult::fromResponse($data);

        // Assert
        static::assertCount(0, $result->getResults());
    }

    /**
     * @covers \Shareforce\Neo4j\Command\Result\CommitTransactionResult::fromResponse
     * @covers \Shareforce\Neo4j\Command\Result\TransactionResult::__construct
     * @covers \Shareforce\Neo4j\Command\Result\TransactionResult::populateTransactionResult
     * @covers \Shareforce\Neo4j\Command\Result\TransactionResult::getResults
     * @covers \Shareforce\Neo4j\Command\Result\ResultParser::parseResults
     */
    public function testResultIsPopulatedWithData(): void
    {
        // Arrange
        $data = [
            'results' => [
                [
                    'columns' => [],
                    'data' => [],
                ],
            ],
        ];

        // Act
        $result = CommitTransactionResult::fromResponse($data);

        // Assert
        static::assertCount(1, $result->getResults());
    }

    /**
     * @covers \Shareforce\Neo4j\Command\Result\CommitTransactionResult::fromResponse
     * @covers \Shareforce\Neo4j\Command\Result\TransactionResult::__construct
     * @covers \Shareforce\Neo4j\Command\Result\TransactionResult::populateTransactionResult
     * @covers \Shareforce\Neo4j\Command\Result\TransactionResult::getTransactionId
     * @covers \Shareforce\Neo4j\Command\Result\ResultParser::parseTransactionId
     */
    public function testTransactionIdIsPopulated(): void
    {
        // Arrange
        $data = ['commit' => '/some/url/with/transaction-id/commit'];

        // Act
        $result = CommitTransactionResult::fromResponse($data);

        // Assert
        static::assertEquals('transaction-id', $result->getTransactionId());
    }

    /**
     * @covers \Shareforce\Neo4j\Command\Result\CommitTransactionResult::fromResponse
     * @covers \Shareforce\Neo4j\Command\Result\TransactionResult::__construct
     * @covers \Shareforce\Neo4j\Command\Result\TransactionResult::populateTransactionResult
     * @covers \Shareforce\Neo4j\Command\Result\TransactionResult::getTransactionId
     * @covers \Shareforce\Neo4j\Command\Result\ResultParser::parseTransactionId
     */
    public function testTransactionIdIsNotPopulatedWhenNoCommitKey(): void
    {
        // Arrange
        $data = [];

        // Act
        $result = CommitTransactionResult::fromResponse($data);

        // Assert
        static::assertNull($result->getTransactionId());
    }

    /**
     * @covers \Shareforce\Neo4j\Command\Result\CommitTransactionResult::fromResponse
     * @covers \Shareforce\Neo4j\Command\Result\TransactionResult::__construct
     * @covers \Shareforce\Neo4j\Command\Result\TransactionResult::populateTransactionResult
     * @covers \Shareforce\Neo4j\Command\Result\TransactionResult::getTransactionExpires
     * @covers \Shareforce\Neo4j\Command\Result\ResultParser::parseTransactionExpirationDate
     */
    public function testTransactionExpirationIsNotPopulatedWhenNoTransactionKey(): void
    {
        // Arrange
        $data = [];

        // Act
        $result = CommitTransactionResult::fromResponse($data);

        // Assert
        static::assertNull($result->getTransactionExpires());
    }

    /**
     * @covers \Shareforce\Neo4j\Command\Result\CommitTransactionResult::fromResponse
     * @covers \Shareforce\Neo4j\Command\Result\TransactionResult::__construct
     * @covers \Shareforce\Neo4j\Command\Result\TransactionResult::populateTransactionResult
     * @covers \Shareforce\Neo4j\Command\Result\TransactionResult::getTransactionExpires
     * @covers \Shareforce\Neo4j\Command\Result\ResultParser::parseTransactionExpirationDate
     */
    public function testTransactionExpirationIsNotPopulatedWhenNoExpiresKey(): void
    {
        // Arrange
        $data = [
            'transaction' => [],
        ];

        // Act
        $result = CommitTransactionResult::fromResponse($data);

        // Assert
        static::assertNull($result->getTransactionExpires());
    }

    /**
     * @covers \Shareforce\Neo4j\Command\Result\CommitTransactionResult::fromResponse
     * @covers \Shareforce\Neo4j\Command\Result\TransactionResult::__construct
     * @covers \Shareforce\Neo4j\Command\Result\TransactionResult::populateTransactionResult
     * @covers \Shareforce\Neo4j\Command\Result\TransactionResult::getTransactionExpires
     * @covers \Shareforce\Neo4j\Command\Result\ResultParser::parseTransactionExpirationDate
     */
    public function testTransactionExpirationIsNotPopulatedWithInvalidExpiresKey(): void
    {
        // Arrange
        $data = [
            'transaction' => ['expires' => 'invalid'],
        ];

        // Act
        $result = CommitTransactionResult::fromResponse($data);

        // Assert
        static::assertNull($result->getTransactionExpires());
    }

    /**
     * @covers \Shareforce\Neo4j\Command\Result\CommitTransactionResult::fromResponse
     * @covers \Shareforce\Neo4j\Command\Result\TransactionResult::__construct
     * @covers \Shareforce\Neo4j\Command\Result\TransactionResult::populateTransactionResult
     * @covers \Shareforce\Neo4j\Command\Result\TransactionResult::getTransactionExpires
     * @covers \Shareforce\Neo4j\Command\Result\ResultParser::parseTransactionExpirationDate
     */
    public function testTransactionExpirationIsNotPopulatedWithValidExpiresKey(): void
    {
        // Arrange
        $data = [
            'transaction' => [
                'expires' => date('D, j M Y H:i:s e'),
            ],
        ];

        // Act
        $result = CommitTransactionResult::fromResponse($data);

        // Assert
        static::assertNotNull($result->getTransactionExpires());
    }
}
