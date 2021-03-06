<?php

/**
 * (c) linshaowl <linshaowl@163.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lswl\Api\Utils;

use Lswl\Api\Contracts\ConstAttributeInterface;
use Lswl\Api\Contracts\ResultCodeInterface;
use Lswl\Api\Utils\Cipher\Token as TokenCipher;

/**
 * 令牌加密
 */
class Token
{
    /**
     * 获取token
     * @param string $name
     * @return string
     */
    public static function get(string $name = 'token'): string
    {
        $token = request()->bearerToken();
        if (empty($token)) {
            $token = RequestParams::getParam(request(), $name, '');
        }

        return $token;
    }

    /**
     * 创建token
     * @param int $id
     * @param string $scene
     * @return string
     */
    public static function create(int $id, string $scene = ConstAttributeInterface::DEFAULT_SCENE): string
    {
        $id .= ':' . time() . ':' . $scene;
        return TokenCipher::getInstance()
            ->encrypt($id);
    }

    /**
     * 解析token
     * [加密字符, 加密时间, 加密场景]
     * @param string $token
     * @return array
     */
    public static function parse(string $token): array
    {
        $str = TokenCipher::getInstance()
            ->decrypt($token);
        return explode(':', $str);
    }

    /**
     * 验证token
     * @param array $parse
     * @param string $scene
     * @return array|bool
     */
    public static function verify(array $parse, string $scene = ConstAttributeInterface::DEFAULT_SCENE)
    {
        // 验证格式
        if (count($parse) != 3) {
            return [ResultCodeInterface::TOKEN_INVALID, trans('token_invalid')];
        }

        // [加密字符, 加密时间, 加密场景]
        [, $time, $parseScene] = $parse;

        // 场景不同
        if ($parseScene != $scene) {
            return [ResultCodeInterface::TOKEN_INVALID, trans('token_invalid')];
        }

        // 验证token是否有效
        $refreshTime = config('lswl-api.token.allow_refresh_time', 0);
        if (((int)$time + $refreshTime) < time()) {
            return [ResultCodeInterface::TOKEN_EXPIRE, trans('token_expired')];
        }

        return true;
    }
}
