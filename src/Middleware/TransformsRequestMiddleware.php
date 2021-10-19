<?php

/**
 * (c) linshaowl <linshaowl@163.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lswl\Api\Middleware;

use Illuminate\Foundation\Http\Middleware\TransformsRequest as BaseTransformsRequest;
use Lswl\Api\Contracts\RequestParamsInterface;
use Lswl\Support\Utils\Collection;
use Symfony\Component\HttpFoundation\ParameterBag;

/**
 * 转换请求参数中间件
 */
class TransformsRequestMiddleware extends BaseTransformsRequest
{
    protected function clean($request)
    {
        // 参数
        $parameter = new ParameterBag(app()->get(RequestParamsInterface::class)->toArray());
        $this->cleanParameterBag($parameter);

        // 请求参数
        app()->instance(RequestParamsInterface::class, new Collection($parameter));
    }
}
