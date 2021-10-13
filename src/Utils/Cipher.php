<?php

/**
 * (c) linshaowl <linshaowl@163.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lswl\Api\Utils;

use Lswl\Api\Utils\Cipher\Runtime;

/**
 * 运行加、解密
 */
class Cipher
{
    /**
     * 请求
     * @param string $params
     * @return string
     */
    public static function request(string $params): string
    {
        if (static::isExec()) {
            return Runtime::getInstance()
                ->decrypt($params);
        }

        return $params;
    }

    /**
     * 响应
     * @param array $data
     * @return array|string
     */
    public static function response(array $data)
    {
        if (static::isExec()) {
            return Runtime::getInstance()
                ->encrypt(json_encode($data));
        }

        return $data;
    }

    /**
     * 是否运行
     * @return bool
     */
    protected static function isExec(): bool
    {
        return !config(
            'lswl-api.runtime.debug',
            true
        );
    }
}
