<?php

/**
 * (c) linshaowl <linshaowl@163.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lswl\Api\Contracts;

/**
 * 请求结果状态码
 */
interface ResultCodeInterface
{
    public const HTTP_SUCCESS_CODE = 200;
    public const HTTP_ERROR_CODE = 200;

    public const SUCCESS = 2000;
    public const NO_DATA = 2001;
    public const MAINTENANCE = 3000;
    public const ERROR = 4000;
    public const SDL = 4001;
    public const TOKEN_NO_EXISTS = 4002;
    public const TOKEN_INVALID = 4003;
    public const TOKEN_EXPIRE = 4004;
    public const PROHIBIT_LOGIN = 4005;
    public const REQUEST_LOCKED = 4006;
    public const SYS_EXCEPTION = 5000;
    public const SYS_ERROR = 5001;
    public const OLD_VERSION = 6000;
}
