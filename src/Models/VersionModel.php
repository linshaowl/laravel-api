<?php

/**
 * (c) linshaowl <linshaowl@163.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lswl\Api\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Lswl\Api\Contracts\VersionModelInterface;

/**
 * 版本模型
 */
class VersionModel extends BaseModel implements VersionModelInterface
{
    /**
     * 是否强制更新获取器
     * @param mixed $value
     * @return bool
     */
    protected function getIsForceAttribute($value): bool
    {
        return !!$value;
    }

    /**
     * 获取最新版本信息
     * @return Builder|Model|object|null
     */
    public function getLastInfo()
    {
        return static::query()
            ->first();
    }

    /**
     * 通过平台获取最新版本信息
     * @param int $platform
     * @return Builder|Model|object|null
     */
    public function getLastInfoByPlatform(int $platform)
    {
        return static::query()
            ->where('platform', $platform)
            ->first();
    }
}
