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
     * 抛出成功异常
     * @param $data
     * @param string $msg
     * @param int $code
     * @return Response|ResponseFactory
     */
    public static function success($data = null, string $msg = '', int $code = ResultCodeInterface::SUCCESS)
    {
        $msg = !empty($msg) ? $msg : lswl_api_lang_messages_trans('success');
        return static::abort($code, $msg, $data);
    }

    /**
     * 抛出失败异常
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
        return static::abort($code, $msg, $data, $httpCode);
    }

    /**
     * 抛出无数据异常
     * @param string $msg
     * @return Response|ResponseFactory
     */
    public static function noData(string $msg = '')
    {
        $msg = !empty($msg) ? $msg : lswl_api_lang_messages_trans('no_data');
        return static::abort(ResultCodeInterface::NO_DATA, $msg, null);
    }

    /**
     * 抛出异常
     * @param int $code
     * @param string $msg
     * @param $data
     * @param int $httpCode
     * @return Response|ResponseFactory
     */
    private static function abort(int $code, string $msg, $data, int $httpCode = ResultCodeInterface::HTTP_SUCCESS_CODE)
    {
        $response = [
            'code' => $code,
            'msg' => $msg,
            'data' => $data,
        ];

        return response()->json($response, $httpCode, [], JSON_UNESCAPED_UNICODE);
    }
}
