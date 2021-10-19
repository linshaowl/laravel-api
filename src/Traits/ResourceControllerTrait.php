<?php

/**
 * (c) linshaowl <linshaowl@163.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lswl\Api\Traits;

/**
 * 资源控制器方法
 */
trait ResourceControllerTrait
{
    use ControllerWithServiceTrait;

    public function index()
    {
        return $this->service->index();
    }

    public function show(int $id)
    {
        if (empty($this->params->id)) {
            $this->params->id = $id;
        }

        return $this->service->show();
    }

    public function store()
    {
        return $this->service->store();
    }

    public function update(string $id = '')
    {
        if (empty($this->params->id)) {
            $this->params->id = $id;
        }

        return $this->service->update();
    }

    public function destroy(string $id = '')
    {
        if (empty($this->params->id)) {
            $this->params->id = $id;
        }

        return $this->service->destroy();
    }
}
