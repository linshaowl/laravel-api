<?php

/**
 * (c) linshaowl <linshaowl@163.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lswl\Api\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Lswl\Database\Contracts\DatabaseInterface;
use Lswl\Database\Traits\DatabaseTrait;

/**
 * 基础中间模型
 * @method DatabaseTrait initialize()
 */
class BasePivot extends Pivot implements DatabaseInterface
{
    use DatabaseTrait;

    protected function initializeBefore()
    {
        // 设置当前表名
        if (empty($this->table)) {
            $this->setTable(static::getSnakeSingularName());
        }
    }
}
