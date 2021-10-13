<?php

/**
 * (c) linshaowl <linshaowl@163.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lswl\Api\Middleware;

/**
 * 转换空字符串为 null
 */
class ConvertEmptyStringsToNullMiddleware extends TransformsRequestMiddleware
{
    protected function transform($key, $value)
    {
        return $value === '' ? null : $value;
    }
}
