# Phpredis-Bloom
RedisBloom for PHP

## Intro
Phpredis-Bloom provides the full set of commands for [RedisBloom Module](https://oss.redislabs.com/redisbloom/). 
It's built on top of the [phpredis](https://github.com/phpredis/phpredis) and use it as Redis client, 
so you can also take advantage of some of the features included in `phpredis` as Redis client.

## Requirements
- Redis server 4.0+ version (Redis Modules are only available from Redis 4.0+)
- RedisBloom Module installed on Redis server as specified in [Building and running](https://oss.redislabs.com/redisbloom/Quick_Start/#building-and-running)
- PHP 7.2+ with PHP Redis extension 5 installed

## Usage

There are 2 ways to execute Phpredis-bloom commands:

`Executing commands by using RedisBloomClient`
```
use Averias\RedisBloom\Factory\RedisBloomFactory;

// instantiate a RedisBloomClient from RedisBloomFactory with default connection options
$factory = new RedisBloomFactory();
$client = $factory->createClient();

// then you can execute whatever redis bloom command for each of the 4 data types
$result = $client->bloomFilterAdd('filter-key', 'item-15');

```

`Executing Bloom Filter commands by using BloomFilter datatype`

```
use Averias\RedisBloom\Factory\RedisBloomFactory;

// instantiate a BloomFilter class from RedisBloomFactory with default connection options
$factory = new RedisBloomFactory();
$bloomFilter = $factory->createBloomFilter('filter-key');

// then you can execute whatever Bloom Filter command on 'filter-key' filter
// adding one item to Bloom Filter 'filter-key'
$result = $bloomFilter->add('item1'); // returns true

// adding 2 items more to Bloom Filter 'filter-key'
$result = $bloomFilter->multiAdd('item2', 15); // returns and array [true, true]

// checking if item 'item1' exists in 'filter-key' Bloom Filter
$result = $bloomFilter->exists('item1'); // returns true

// adding one item more
$result = $bloomFilter->add(17.2); // returns true

// checking if a list items exist in 'filter-key' Bloom Filter
$result = $bloomFilter->multiExists('item1', 15, 'foo'); // returns and array [true, true, false] since 'foo' doesn't exists 
```

### Example

The following code snippet show how to instantiate RedisBloom clients and BloomFilter data type with different 
connection configurations
 
```
<?php

use Averias\RedisBloom\Enum\Connection;
use Averias\RedisBloom\Factory\RedisBloomFactory;

require(dirname(__DIR__).'/vendor/autoload.php');

const EXAMPLE_FILTER = 'example-filter';

/**
 * Default connection params:
 * [
 *     'host' => '127.0.0.1',
 *     'port' => 6379,
 *     'timeout' => 0.0, // seconds
 *     'retryInterval' => 15 // milliseconds
 *     'readTimeout' => 2, // seconds
 *     'persistenceId' => null // string for persistent connections, null for no persistent ones
 *     'database' => 0 // Redis database index [0..15]
 * ]
 *
 * you can create a factory with default connection configuration by not passing any param in the constructor
 * $defaultFactory = new RedisBloomFactory();
 */

// create a factory with default connection configuration but pointing to database 15
$factoryDB15 = new RedisBloomFactory([Connection::DATABASE => 15]);

// it creates a RedisBloomClient with same default connection configuration as specified in factory above
$clientDB15 = $factoryDB15->createClient();

// using the same factory you can create a BloomFilter object pointing to database 14 and filter name = 'example-filter'
$bloomFilterDB14 = $factoryDB15->createBloomFilter(EXAMPLE_FILTER, [Connection::DATABASE => 14]);

// add 'item-15' to 'example-filter' bloom filter on database 15
$clientDB15->bloomFilterAdd(EXAMPLE_FILTER, 'item-15');

// add 'item-14' to 'example-filter' bloom filter on database 14
$bloomFilterDB14->add('item-14');

// create another RedisBloomClient pointing to database 14
$clientDB14 = $factoryDB15->createClient([Connection::DATABASE => 14]);

$result = $clientDB15->bloomFilterExists(EXAMPLE_FILTER, 'database15'); //true
$result = $clientDB15->bloomFilterExists(EXAMPLE_FILTER, 'database14'); // false

$result = $clientDB14->bloomFilterExists(EXAMPLE_FILTER, 'database15'); // false
$result = $clientDB14->bloomFilterExists(EXAMPLE_FILTER, 'database14'); // true

// delete bloom filter on database 15
$clientDB15->executeRawCommand('DEL', EXAMPLE_FILTER);

// delete bloom filter on database 14
$clientDB14->del(EXAMPLE_FILTER);

```

## Commands
#### Phpredis-Bloom commands

Phpredis-bloom provides all the commands for the four RedisBloom data types, please follow the links below for a 
detailed info for each one:

- [Bloom Filter](https://github.com/averias/phpredis-bloom/blob/master/docs/BLOOM-FILTER-COMMANDS.md)
- Cuckoo Filter (still under development)
- Mins-Sketch (still under development)
- Top-K (still under development)

#### Phpredis commands

You can send Redis commands as specified in [phpredis documentation](https://github.com/phpredis/phpredis#table-of-contents)

#### Raw commands
You can send whatever you want to Redis by using `RedisBloomClient::executeRawCommand`:
```
// raw Redis Bloom command
$client->executeRawCommand(BloomCommands::BF_ADD, 'filter-name', 'value');

// raw Redis command
$client->executeRawCommand('hget, 'hash-key', 'foo');
``` 

## Tests
#### On a local Redis server 4.0+ with RedisBloom module and Redis extension 5 installed
From console run the following command from the root directory of this project:

`./vendor/bin/phpunit`

if you don't have configured your local Redis server in 127.0.0.1:6379 you can set REDIS_TEST_SERVER, REDIS_TEST_PORT 
and REDIS_TEST_DATABASE in `./phpunit.xml` file with your local Redis host, port and database before running the above 
command.
  
#### Docker
Having Docker installed, run the following command in the root directory of this project:

`bash run-tests-docker.sh`

by running the above bash script, two docker services will be built, one with PHP 7.2 with xdebug and redis extensions
enabled and another with the image of `redislab\rebloom:2.0.3` (Redis server 5 with RedisBloom module installed). 
Then the tests will run inside `phpredis-bloom` docker service container and finally both container will be stopped.

## Examples
- [Factory](https://github.com/averias/phpredis-bloom/blob/master/examples/factory.php)
- [Scan](https://github.com/averias/phpredis-bloom/blob/master/examples/scan.php)

## License
Phpredis-Bloom code is distributed under MIT license, see [LICENSE](https://github.com/averias/phpredis-bloom/blob/master/LICENSE) 
file