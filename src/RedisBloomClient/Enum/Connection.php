<?php
/**
 * @project   phpredis-bloom
 * @author    Rafael Campoy <rafa.campoy@gmail.com>
 * @copyright 2019 Rafael Campoy <rafa.campoy@gmail.com>
 * @license   MIT
 * @link      https://github.com/averias/phpredis-bloom
 *
 * Copyright and license information, is included in
 * the LICENSE file that is distributed with this source code.
 */

namespace Averias\RedisBloom\Enum;

use MyCLabs\Enum\Enum;

class Connection extends Enum
{
    const HOST = 'host';
    const PORT = 'port';
    const TIMEOUT = 'timeout';
    const RETRY_INTERVAL = 'retryInterval';
    const READ_TIMEOUT = 'readTimeout';
    const PERSISTENCE_ID = 'persistenceId';
    const PASSWORD = 'password';
    const DATABASE = 'database';

    const DEFAULT = [
        self::HOST => '127.0.0.1',
        self::PORT => 6379,
        self::TIMEOUT => 0.0,
        self::RETRY_INTERVAL => 0,
        self::READ_TIMEOUT => 0,
        self::PERSISTENCE_ID => null,
        self::PASSWORD => null,
        self::DATABASE => 0
    ];
}
