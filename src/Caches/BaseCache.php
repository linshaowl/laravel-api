<?php

/**
 * (c) linshaowl <linshaowl@163.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lswl\Api\Caches;

use Illuminate\Redis\Connections\PhpRedisConnection;
use Lswl\Support\Helper\RedisConnectionHelper;
use Lswl\Support\Traits\InstanceTrait;

/**
 * 基础缓存
 */
class BaseCache
{
    use InstanceTrait;

    /**
     * @var PhpRedisConnection
     */
    protected $connection;

    public function __construct()
    {
        $this->connection = RedisConnectionHelper::getPhpRedis();
    }

    /**
     * 获取过期时间
     * @return int
     */
    protected function getExpireTime(): int
    {
        $current = date('Y-m-d');
        return strtotime("$current +1 day") - time();
    }
}
