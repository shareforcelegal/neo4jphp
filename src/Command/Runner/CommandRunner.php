<?php

declare(strict_types=1);

namespace Shareforce\Neo4j\Command\Runner;

use Shareforce\Neo4j\Command\Command;
use Shareforce\Neo4j\Command\Result\CommandResult;

interface CommandRunner
{
    public function run(Command $command): CommandResult;
}
