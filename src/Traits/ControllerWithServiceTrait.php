<?php

/**
 * (c) linshaowl <linshaowl@163.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lswl\Api\Traits;

use Lswl\Api\Utils\ParseService;
use BadMethodCallException;
use Throwable;

/**
 * 控制器添加服务辅助
 */
trait ControllerWithServiceTrait
{
    protected $service;

    protected function initialize()
    {
        try {
            parent::initialize();
        } catch (Throwable $e) {
        }
        $this->withService();
    }

    /**
     * 添加 service 属性
     * @return $this
     */
    private function withService()
    {
        $this->service = ParseService::run($this->service, get_called_class(), 'controller');

        return $this;
    }

    public function __call($method, $parameters)
    {
        if (is_null($this->service)) {
            $this->withService();
        }

        if (method_exists($this->service, $method)) {
            return $this->service->{$method}(...$parameters);
        }

        try {
            parent::__call($method, $parameters);
        } catch (Throwable $e) {
        }

        throw new BadMethodCallException(
            sprintf(
                'Method %s::%s does not exist.',
                static::class,
                $method
            )
        );
    }
}
