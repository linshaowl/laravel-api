<?php

/**
 * (c) linshaowl <linshaowl@163.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lswl\Api\Contracts;

/**
 * 用户模型
 */
interface UserModelInterface
{
    // 通过id获取信息
    public function getInfoById(int $id);
}
