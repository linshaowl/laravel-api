<?php

/**
 * (c) linshaowl <linshaowl@163.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lswl\Api\Utils;

use Illuminate\Database\Eloquent\Model;

/**
 * 解析 Model
 */
class ParseModel
{
    /**
     * 运行
     * @param $model
     * @param string $class
     * @param string $suffix
     * @return Model|mixed
     */
    public static function run($model, string $class, string $suffix)
    {
        if ($model instanceof Model) {
            return $model;
        }

        // 尝试调用
        $call = static::callModel($model);
        if ($call instanceof Model) {
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
        $replace = str_replace(ucfirst($suffix), 'Model', $namespace) . $name;

        // 不带 Model 后缀
        $call = static::callModel($replace);
        if ($call instanceof Model) {
            return $call;
        }

        // 带 Model 后缀
        $call = static::callModel($replace . 'Model');
        if ($call instanceof Model) {
            return $call;
        }

        return $model;
    }

    /**
     * 调用 model
     * @param $model
     * @return mixed
     */
    protected static function callModel($model)
    {
        $res = $model;
        if (is_string($model) && class_exists($model)) {
            $res = new $model();
        }

        return $res;
    }
}
