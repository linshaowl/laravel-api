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
use Illuminate\Support\Facades\Cache;
use Lswl\Api\Contracts\RequestParamsInterface;
use Lswl\Api\Contracts\ResultCodeInterface;
use Lswl\Api\Traits\ResultThrowTrait;
use Lswl\Support\Utils\Helper;

/**
 * 请求锁定中间件
 */
class RequestLockMiddleware
{
    use ResultThrowTrait;

    public function handle(Request $request, Closure $next, $useSign = false)
    {
        // 跨域请求
        if ($request->getMethod() === 'OPTIONS') {
            return $next($request);
        }

        // 请求锁定对象实例
        $request->requestLock = Cache::store(
            config('lswl-api.request_lock.driver', 'redis')
        )->lock(
            $this->getLockKey($request, Helper::boolean($useSign)),
            config('lswl-api.request_lock.seconds', 5)
        );

        if (!$request->requestLock->get()) {
            $this->error(
                trans('request_lock_prompt'),
                ResultCodeInterface::REQUEST_LOCKED
            );
        }

        return $next($request);
    }

    /**
     * 获取锁定标识
     * @param Request $request
     * @param bool $useSign
     * @return string|null
     */
    protected function getLockKey(Request $request, bool $useSign): ?string
    {
        $sign = $useSign ? $request->input('sign', '') : '';
        $key = md5(
            $request->ip() . $request->path() . $sign . json_encode(app()->get(RequestParamsInterface::class))
        );
        return 'lock:' . $key;
    }
}
