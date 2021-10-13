<?php

/**
 * (c) linshaowl <linshaowl@163.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lswl\Api\Traits;

use Illuminate\Database\Eloquent\Model;
use Lswl\Support\Utils\Collection;

/**
 * 用户信息
 */
trait UserInfoTrait
{
    /**
     * 登录用户id
     * @var int
     */
    protected $userId = 0;

    /**
     * 登录用户信息
     * @var Collection|Model
     */
    protected $userInfo;

    /**
     * 初始化操作
     */
    protected function initialize()
    {
        $this->userInfo = $this->request->userInfo ?? new Collection();
        $this->userId = $this->userInfo->id ?? 0;
    }
}
