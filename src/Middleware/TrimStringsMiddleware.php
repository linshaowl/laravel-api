<?php

/**
 * (c) linshaowl <linshaowl@163.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lswl\Api\Middleware;

/**
 * trim 请求参数中间件
 */
class TrimStringsMiddleware extends TransformsRequestMiddleware
{
    /**
     * 不需要 trim 的字段
     * @var array
     */
    protected $except = [];

    /**
     * trim 请求参数
     * @param string $key
     * @param mixed $value
     * @return mixed
     */
    protected function transform($key, $value)
    {
        if (in_array($key, $this->except, true)) {
            return $value;
        }

        return is_string($value) ? trim($value) : $value;
    }
}
