<?php

/**
 * (c) linshaowl <linshaowl@163.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lswl\Api\Controllers;

use Illuminate\Routing\Controller;
use Lswl\Api\Traits\RequestInfoTrait;
use Lswl\Api\Traits\UserInfoTrait;

/**
 * 基础控制器
 */
class BaseController extends Controller
{
    use RequestInfoTrait;
    use UserInfoTrait;

    public function __construct()
    {
        // 设置请求信息
        $this->setRequestInfo();
    }

    /**
     * {@inheritdoc}
     */
    public function callAction($method, $parameters)
    {
        $this->initialize();
        return parent::callAction($method, $parameters);
    }
}
