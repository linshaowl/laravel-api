<?php

/**
 * (c) linshaowl <linshaowl@163.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lswl\Api\Utils;

use Lswl\Api\Contracts\ServiceInterface;

/**
 * 解析 Service
 */
class ParseService
{
    /**
     * 运行
     * @param $service
     * @param string $class
     * @param string $suffix
     * @return ServiceInterface|mixed
     */
    public static function run($service, string $class, string $suffix)
    {
        if ($service instanceof ServiceInterface) {
            return $service;
        }

        // 尝试调用
        $call = static::callService($service);
        if ($call instanceof ServiceInterface) {
            return $call;
        }

        // 重新组装名字
        $className = class_basename($class);
        $namespace = preg_replace('/' . $className . '$/', '', $class);
        $name = preg_replace(
            sprintf('/%s$/i', $suffix),
            '',
            $className
        );
        $replace = str_replace(ucfirst($suffix), 'Service', $namespace) . $name;

        // 不带 Service 后缀
        $call = static::callService($replace);
        if ($call instanceof ServiceInterface) {
            return $call;
        }

        // 带 Service 后缀
        $call = static::callService($replace . 'Service');
        if ($call instanceof ServiceInterface) {
            return $call;
        }

        return $service;
    }

    /**
     * 调用 service
     * @param $service
     * @return mixed
     */
    protected static function callService($service)
    {
        $res = $service;
        if (is_string($service) && class_exists($service)) {
            if (method_exists($service, 'getInstance')) {
                $res = call_user_func([$service, 'getInstance']);
            } else {
                $res = new $service();
            }
        }

        return $res;
    }
}
