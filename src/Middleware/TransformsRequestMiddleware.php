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
use Symfony\Component\HttpFoundation\ParameterBag;

/**
 * 转换请求参数中间件
 */
class TransformsRequestMiddleware extends BaseTransformsRequest
{
    protected function clean($request)
    {
        $this->cleanParameterBag(
            new ParameterBag(app()->get(RequestParamsInterface::class)->toArray())
        );
    }
}
