# Quick Start

## Installation

To install this package in your application, use
[Composer](https://getcomposer.org):

```bash
$ composer require shareforcelegal/neo4jphp
```

## Usage

Setting up a connection:

```php
use Shareforce\Neo4j\Command\Runner\SingleCommandRunner;
use Shareforce\Neo4j\Connection\Client;
use Shareforce\Neo4j\Connection\Options;
use Shareforce\Neo4j\Transport\GuzzleTransport;

$options = Options::create([
    'host' => '',
    'database' => '',
    'username' => '',
    'password' => '',
]);

$transport = new GuzzleTransport(new \GuzzleHttp\Client());
$uriFactory = $transport;
$requestFactory = $transport;

$client = new Client(
    $options, 
    new SingleCommandRunner(
        $options, 
        $transport, 
        $uriFactory, 
        $requestFactory
    )
);

$client->executeQuery('...');
```
