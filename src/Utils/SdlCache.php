<?php

/**
 * (c) linshaowl <linshaowl@163.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lswl\Api\Utils;

use Illuminate\Redis\Connections\PhpRedisConnection;
use Lswl\Api\Contracts\ConstAttributeInterface;
use Lswl\Support\Helper\RedisConnectionHelper;
use Lswl\Support\Traits\InstanceTrait;

/**
 * 单设备缓存
 */
class SdlCache
{
    use InstanceTrait;

    /**
     * @var PhpRedisConnection
     */
    protected $connection;

    /**
     * 场景
     * @var string
     */
    protected $scene = ConstAttributeInterface::DEFAULT_SCENE;

    protected static $cacheKey = 'sdl:%s%d';
    protected static $cacheTmpKey = 'sdl:tmp:%s%d';

    public function __construct()
    {
        $this->connection = RedisConnectionHelper::getPhpRedis();
    }

    /**
     * 设置场景
     * @param string $scene
     * @return $this
     */
    public function scene(string $scene)
    {
        $this->scene = $scene;
        return $this;
    }

    /**
     * 获取缓存数据
     * @param int $id
     * @return array
     */
    public function get(int $id): array
    {
        return array_filter(
            [
                $this->connection->get($this->getCacheKey($id)),
                $this->connection->get($this->getCacheTmpKey($id)),
            ]
        );
    }

    /**
     * 设置缓存数据
     * @param int $id
     * @param string $token
     * @param string $oldToken
     * @return bool
     */
    public function set(int $id, string $token, string $oldToken = ''): bool
    {
        $this->connection->set($this->getCacheKey($id), $token);

        // 旧token存在
        if (!empty($oldToken)) {
            $this->connection->setex($this->getCacheTmpKey($id), config('lswl-api.sdl_tmp_expire', 10), $oldToken);
        }

        return true;
    }

    /**
     * 删除缓存数据
     * @param int $id
     * @return int
     */
    public function del(int $id): int
    {
        return $this->connection->del([$this->getCacheKey($id), $this->getCacheTmpKey($id)]);
    }

    /**
     * 验证是否通过
     * @param int $id
     * @param string $token
     * @return bool
     */
    public function verify(int $id, string $token): bool
    {
        return in_array($token, $this->get($id));
    }

    /**
     * 获取缓存key
     * @param int $id
     * @return string
     */
    protected function getCacheKey(int $id): string
    {
        // 当前场景
        $scene = $this->scene ? $this->scene . '_' : '';
        return sprintf(static::$cacheKey, $scene, $id);
    }

    /**
     * 获取临时key
     * @param int $id
     * @return string
     */
    protected function getCacheTmpKey(int $id): string
    {
        // 当前场景
        $scene = $this->scene ? $this->scene . '_' : '';
        return sprintf(static::$cacheTmpKey, $scene, $id);
    }
}
