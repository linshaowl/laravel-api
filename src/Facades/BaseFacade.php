<?php

/**
 * (c) linshaowl <linshaowl@163.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lswl\Api\Facades;

use ReflectionMethod;
use RuntimeException;

/**
 * 基础门面
 */
abstract class BaseFacade
{
    /**
     * 获取组件的注册名称
     * @return object|string
     */
    protected static function getFacadeAccessor()
    {
        throw new RuntimeException('Facade does not implement getFacadeAccessor method.');
    }

    /**
     * 获取门面根对象
     * @return mixed
     */
    protected static function getFacadeRoot()
    {
        $name = static::getFacadeAccessor();

        if (is_object($name)) {
            return $name;
        }

        if (app()->has($name)) {
            return app()->get($name);
        }

        if (class_exists($name)) {
            if (!method_exists($name, 'getInstance')) {
                return app()->instance($name, new $name());
            }

            if ((new ReflectionMethod($name, 'getInstance'))->isStatic()) {
                return $name::getInstance();
            }
        }

        return false;
    }

    public static function __callStatic($method, $args)
    {
        $instance = static::getFacadeRoot();

        if (!$instance) {
            throw new RuntimeException('A facade root has not been set.');
        }

        return $instance->$method(...$args);
    }
}
