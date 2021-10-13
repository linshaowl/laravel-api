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
use Lswl\Log\Log;
use Lswl\Support\Utils\RequestInfo;

/**
 * 请求日志中间件
 */
class RequestLogMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // 记录请求日志
        Log::dir('request')
            ->name('')
            ->withDateToName()
            ->withMessageLineBreak()
            ->debug(RequestInfo::get());

        return $next($request);
    }
}
