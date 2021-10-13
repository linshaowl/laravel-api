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
use Lswl\Api\Contracts\UserModelInterface;

/**
 * 用户模型
 */
class UserModel extends BaseModel implements UserModelInterface
{
    /**
     * 通过id获取信息
     * @param int $id
     * @return Builder|Model|object|null
     */
    public function getInfoById(int $id)
    {
        return static::query()
            ->where('id', $id)
            ->first();
    }
}
