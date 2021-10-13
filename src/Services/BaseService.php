<?php

/**
 * (c) linshaowl <linshaowl@163.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lswl\Api\Services;

use Lswl\Api\Contracts\ConstAttributeInterface;
use Lswl\Api\Contracts\ServiceInterface;
use Lswl\Api\Traits\RequestInfoTrait;
use Lswl\Api\Traits\ResultThrowTrait;
use Lswl\Api\Traits\UserInfoTrait;
use Lswl\Support\Traits\InstanceTrait;

/**
 * 基础服务
 */
class BaseService implements ConstAttributeInterface, ServiceInterface
{
    use InstanceTrait;
    use ResultThrowTrait;
    use RequestInfoTrait;
    use UserInfoTrait;

    public function __construct()
    {
        // 设置请求信息
        $this->setRequestInfo();
        $this->initialize();
    }
}
