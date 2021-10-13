<?php

/**
 * (c) linshaowl <linshaowl@163.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lswl\Api\Daos;

use Lswl\Api\Contracts\ConstAttributeInterface;
use Lswl\Api\Traits\DaoSelectTrait;
use Lswl\Api\Traits\DaoTrait;
use Lswl\Support\Traits\InstanceTrait;

/**
 * 基础数据访问
 */
class BaseDao implements ConstAttributeInterface
{
    use InstanceTrait;
    use DaoTrait;
    use DaoSelectTrait;

    public function __construct()
    {
        $this->initialize();
    }
}
