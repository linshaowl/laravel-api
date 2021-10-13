<?php

/**
 * (c) linshaowl <linshaowl@163.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lswl\Api\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Lswl\Api\Utils\RequestParams;

/**
 * 基础表单请求
 */
class BaseRequest extends FormRequest
{
    /**
     * 验证数据
     * @return mixed|string
     */
    public function validationData()
    {
        return RequestParams::run($this);
    }
}
