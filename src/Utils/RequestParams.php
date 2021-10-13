<?php

/**
 * (c) linshaowl <linshaowl@163.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lswl\Api\Utils;

use Illuminate\Http\Request;

/**
 * 请求参数
 */
class RequestParams
{
    /**
     * 运行
     * @param Request $request
     * @return mixed|string
     */
    public static function run(Request $request)
    {
        // 是否直接存在json格式的params参数
        $jsonParams = json_decode($request->input('params', ''), true);

        // 请求参数
        $params = $jsonParams ?? $request->all();

        // 请求解密
        if ($request->exists('params') && !$jsonParams) {
            $params = Cipher::request($request->input('params'));
        }

        return $params;
    }
}
