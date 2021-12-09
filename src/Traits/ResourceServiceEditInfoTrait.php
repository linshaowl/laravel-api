<?php

/**
 * (c) linshaowl <linshaowl@163.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lswl\Api\Traits;

use Illuminate\Database\Eloquent\Model;

/**
 * 资源服务编辑信息方法
 */
trait ResourceServiceEditInfoTrait
{
    /**
     * 编辑信息查询字段
     * @var array
     */
    protected $editInfoColumns = ['*'];

    /**
     * 编辑信息查询结果
     * @var Model
     */
    protected $editInfoQueryResult;

    public function editInfo()
    {
        // 初始化查询构造器
        $this->initQuery();

        // 前置操作
        $this->editInfoBefore();

        // 处理
        $this->editInfoHandler();

        // 后置操作
        $this->editInfoAfter();

        $this->success($this->editInfoQueryResult);
    }

    /**
     * 编辑信息前置操作
     */
    protected function editInfoBefore()
    {
    }

    /**
     * 编辑信息处理
     */
    protected function editInfoHandler()
    {
        // 获取连表查询合格的列
        $columns = $this->query->getModel()->getQualifiedColumns(
            $this->getFillable($this->editInfoColumns)
        );

        // 默认通过主键查询
        $this->editInfoQueryResult = $this->query
            ->where($this->getQualifiedPrimaryKey(), $this->request->route('id'))
            ->firstOr($columns, function () {
                $this->noData();
            });
    }

    /**
     * 编辑信息后置操作
     */
    protected function editInfoAfter()
    {
    }
}
