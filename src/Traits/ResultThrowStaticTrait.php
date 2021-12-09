<?php

/**
 * (c) linshaowl <linshaowl@163.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lswl\Api\Traits;

use Lswl\Api\Contracts\ResultCodeInterface;
use Lswl\Api\Exceptions\ResultException;

/**
 * 抛出结果异常-静态
 */
trait ResultThrowStaticTrait
{
    /**
     * 抛出成功异常
     * @param $data
     * @param string $msg
     * @param int $code
     * @throws ResultException
     */
    protected static function success(
        $data = null,
        string $msg = '',
        int $code = ResultCodeInterface::SUCCESS
    ) {
        $msg = !empty($msg) ? $msg : trans('success');
        static::abort($code, $msg, $data);
    }

    /**
     * 抛出失败异常
     * @param string $msg
     * @param int $code
     * @param $data
     * @param int $httpCode
     * @throws ResultException
     */
    protected static function error(
        string $msg,
        int $code = ResultCodeInterface::ERROR,
        $data = null,
        int $httpCode = ResultCodeInterface::HTTP_ERROR_CODE
    ) {
        static::abort($code, $msg, $data, $httpCode);
    }

    /**
     * 抛出无数据异常
     * @param string $msg
     * @throws ResultException
     */
    protected static function noData(string $msg = '')
    {
        $msg = !empty($msg) ? $msg : trans('no_data');
        static::abort(ResultCodeInterface::NO_DATA, $msg, null);
    }

    /**
     * 抛出异常
     * @param int $code
     * @param string $msg
     * @param $data
     * @param int $httpCode
     * @throws ResultException
     */
    private static function abort(
        int $code,
        string $msg,
        $data,
        int $httpCode = ResultCodeInterface::HTTP_SUCCESS_CODE
    ) {
        throw new ResultException($code, $msg, $data, $httpCode);
    }
}
