<?php

/**
 * (c) linshaowl <linshaowl@163.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lswl\Api\Utils;

use Illuminate\Http\Request;
use Lswl\Api\Contracts\PlatformInfoInterface;

/**
 * 请求平台
 */
class RequestPlatform
{
    /**
     * 其他平台关键字
     * @var string
     */
    protected static $other = PlatformInfoInterface::OTHER;

    /**
     * 平台关键字
     * @var array
     */
    protected static $keywordsPlatforms = PlatformInfoInterface::KEYWORDS_PLATFORMS;

    /**
     * 运行
     * @param Request $request
     * @return array
     */
    public static function run(Request $request): array
    {
        // 请求平台
        $requestPlatform = static::getRequestPlatform($request);

        return static::check($requestPlatform);
    }

    /**
     * 检测平台
     * @param string $requestPlatform
     * @return array
     */
    public static function check(string $requestPlatform): array
    {
        // 平台
        $platforms[] = static::$other;
        foreach (static::$keywordsPlatforms as $k => $v) {
            if (preg_match(sprintf('/(%s)/', $k), $requestPlatform, $match) && !empty($match[0])) {
                $platforms[] = $v;
            }
        }

        return $platforms;
    }

    /**
     * 获取请求平台
     * @param Request $request
     * @param string $name
     * @return array|string|null
     */
    public static function getRequestPlatform(Request $request, string $name = 'request_platform')
    {
        $platform = RequestParams::getParam($request, $name, '');
        if (empty($platform)) {
            $platform = $request->server('HTTP_USER_AGENT', '');
        }

        return $platform;
    }
}
