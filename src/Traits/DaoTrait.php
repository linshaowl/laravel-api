<?php

/**
 * (c) linshaowl <linshaowl@163.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lswl\Api\Traits;

use Lswl\Api\Utils\ParseModel;
use Throwable;

trait DaoTrait
{
    protected $model;

    protected function initialize()
    {
        try {
            parent::initialize();
        } catch (Throwable $e) {
        }
        $this->withModel();
    }

    /**
     * 添加 service 属性
     * @return $this
     */
    private function withModel()
    {
        $this->model = ParseModel::run($this->model, get_called_class(), 'dao');

        return $this;
    }
}
