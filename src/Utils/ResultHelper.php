<?php

/**
 * (c) linshaowl <linshaowl@163.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lswl\Api\Utils;

use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;
use Lswl\Api\Contracts\ResultCodeInterface;

/**
 * 返回结果辅助
 */
class ResultHelper
{
    /**
     * 返回成功
     * @param $data
     * @param string $msg
     * @param int $code
     * @return Response|ResponseFactory
     */
    public static function success(
        $data = null,
        string $msg = '',
        int $code = ResultCodeInterface::SUCCESS
    ) {
        $msg = !empty($msg) ? $msg : trans('success');
        return static::response($code, $msg, $data);
    }

    /**
     * 返回失败
     * @param string $msg
     * @param int $code
     * @param $data
     * @param int $httpCode
     * @return Response|ResponseFactory
     */
    public static function error(
        string $msg,
        int $code = ResultCodeInterface::ERROR,
        $data = null,
        int $httpCode = ResultCodeInterface::HTTP_ERROR_CODE
    ) {
        return static::response($code, $msg, $data, $httpCode);
    }

    /**
     * 返回无数据
     * @param string $msg
     * @return Response|ResponseFactory
     */
    public static function noData(string $msg = '')
    {
        $msg = !empty($msg) ? $msg : trans('no_data');
        return static::response(ResultCodeInterface::NO_DATA, $msg, null);
    }

    /**
     * 返回响应
     * @param int $code
     * @param string $msg
     * @param $data
     * @param int $httpCode
     * @return Response|ResponseFactory
     */
    private static function response(
        int $code,
        string $msg,
        $data,
        int $httpCode = ResultCodeInterface::HTTP_SUCCESS_CODE
    ) {
        $response = [
            'code' => $code,
            'msg' => $msg,
            'data' => $data,
        ];

        return response()->json($response, $httpCode, [], JSON_UNESCAPED_UNICODE);
    }
}
