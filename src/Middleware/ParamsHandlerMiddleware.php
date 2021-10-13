<?php

/**
 * (c) linshaowl <linshaowl@163.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lswl\Api\Middleware;

use Closure;
use Illuminate\Http\Request;
use Lswl\Api\Contracts\RequestParamsInterface;
use Lswl\Api\Utils\RequestParams;
use Lswl\Support\Utils\Collection;

/**
 * 请求参数处理中间件
 */
class ParamsHandlerMiddleware
{
    /**
     * 过滤键
     * @var array
     */
    protected $filter = ['sign', 'nonce', 'timestamp', 'file'];

    public function handle(Request $request, Closure $next)
    {
        // 原请求参数
        $request->originParams = RequestParams::run($request);

        // 过滤后参数
        $params = array_filter(
            $request->originParams,
            function ($k) {
                return !in_array($k, $this->filter);
            },
            ARRAY_FILTER_USE_KEY
        );
        app()->instance(RequestParamsInterface::class, new Collection($params));

        return $next($request);
    }
}
