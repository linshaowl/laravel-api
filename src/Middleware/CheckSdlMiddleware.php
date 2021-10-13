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
use Lswl\Api\Contracts\ResultCodeInterface;
use Lswl\Api\Traits\ResultThrowTrait;
use Lswl\Api\Utils\SdlCache;
use Lswl\Api\Utils\Token;

/**
 * 检测单设备登录中间件
 */
class CheckSdlMiddleware
{
    use ResultThrowTrait;

    public function handle(Request $request, Closure $next)
    {
        // 令牌
        $token = Token::get();

        // 令牌或用户id不存在
        if (empty($token) || empty($request->userInfo->id)) {
            return $next($request);
        }

        // [加密字符, 加密时间, 加密场景]
        [, , $scene] = Token::parse($token);

        // 缓存
        $cache = SdlCache::getInstance();

        // 场景存在
        if ($scene) {
            $cache->scene($scene);
        }

        // 验证缓存
        if (!$cache->verify($request->userInfo->id, $token)) {
            $this->error(lswl_api_lang_messages_trans('sdl'), ResultCodeInterface::SDL);
        }

        return $next($request);
    }
}
