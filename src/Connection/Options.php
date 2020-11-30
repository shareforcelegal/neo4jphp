<?php

declare(strict_types=1);

namespace Shareforce\Neo4j\Connection;

use function rtrim;

final class Options
{
    private string $host;
    private string $database;
    private string $username;
    private string $password;

    private function __construct()
    {
        $this->host = 'https://localhost:7687';
        $this->username = '';
        $this->password = '';
    }

    public function getHost(): string
    {
        return $this->host;
    }

    public function getDatabase(): string
    {
        return $this->database;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param array<string, mixed> $options
     */
    public static function create(array $options): Options
    {
        $result = new Options();
        $result->host = rtrim($options['host'], '/');
        $result->database = $options['database'];
        $result->username = $options['username'];
        $result->password = $options['password'];

        return $result;
    }
}
