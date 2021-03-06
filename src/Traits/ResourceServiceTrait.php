<?php

/**
 * (c) linshaowl <linshaowl@163.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lswl\Api\Traits;

use Closure;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Lswl\Database\Contracts\DatabaseInterface;
use Lswl\Database\Traits\BuilderAssembleTrait;
use Lswl\Api\Exceptions\ResultException;
use Lswl\Api\Utils\PaginateHelper;
use Lswl\Api\Utils\ParseModel;
use Lswl\Support\Helper\DBHelper;

/**
 * 资源服务方法
 */
trait ResourceServiceTrait
{
    use RequestInfoTrait;
    use ResultThrowTrait;
    use BuilderAssembleTrait;
    use ResourceServiceHooksTrait;
    use ResourceServicePageTrait;
    use ResourceServiceQueryFilterTrait;

    /**
     * 模型
     */
    protected $model;

    /**
     * 查询构造器
     * @var Builder
     */
    protected $query;

    /**
     * index 查询字段
     * @var array
     */
    protected $indexColumns = ['*'];

    /**
     * show 查询字段
     * @var array
     */
    protected $showColumns = ['*'];

    /**
     * 添加字段
     * @var array
     * ['column', ['column' => 'column', 'default' => '', 'custom' => '']]
     */
    protected $storeColumns = ['*'];

    /**
     * 更新字段
     * @var array
     * ['column', ['column' => 'column', 'default' => '', 'custom' => '']]
     */
    protected $updateColumns = [];

    /**
     * index 查询结果
     * @var LengthAwarePaginator|Builder[]|Collection
     */
    protected $indexQueryResult;

    /**
     * show 查询结果
     * @var Model
     */
    protected $showQueryResult;

    /**
     * 添加数据过滤
     * @var bool|Closure
     */
    protected $storeDataFilter = false;

    /**
     * 更新数据过滤
     * @var bool|Closure
     */
    protected $updateDataFilter = false;

    public function index()
    {
        // 查询前处理
        $this->indexQueryBeforeHandler();

        // 初始化查询构造器
        $this->initQuery();

        // 前置操作
        $this->indexBefore();

        // 查询过滤
        $this->indexQueryFilter();

        // 处理
        $this->indexHandler();

        // 后置操作
        $this->indexAfter();

        $this->success($this->indexSuccessResult($this->indexQueryResult));
    }

    public function show()
    {
        // 初始化查询构造器
        $this->initQuery();

        // 前置操作
        $this->showBefore();

        // 处理
        $this->showHandler();

        // 后置操作
        $this->showAfter();

        $this->success($this->showQueryResult);
    }

    public function store()
    {
        // 初始化查询构造器
        $this->initQuery();

        // 前置操作
        $this->storeBefore();

        // 处理
        $this->storeHandler();

        // 后置操作
        $this->storeAfter();

        $this->success();
    }

    public function update()
    {
        // 初始化查询构造器
        $this->initQuery();

        // 前置操作
        $this->updateBefore();

        // 处理
        $this->updateHandler();

        // 后置操作
        $this->updateAfter();

        $this->success();
    }

    public function destroy()
    {
        // 初始化查询构造器
        $this->initQuery();

        // 前置操作
        $this->destroyBefore();

        // 处理
        $this->destroyHandler();

        // 后置操作
        $this->destroyAfter();

        $this->success();
    }

    /**
     * index 查询前处理
     */
    protected function indexQueryBeforeHandler()
    {
        // 处理是否启用分页
        $this->handleIsEnablePage();
        // 处理是否返回分页字段
        $this->handlerIsResultPageField();
    }

    /**
     * index 处理
     * @throws ResultException
     */
    protected function indexHandler()
    {
        // 获取填充字段
        $columns = $this->getFillable($this->indexColumns);

        // 参数
        $params = $this->params->toArray();

        // 组装排序
        static::assembleOrder($this->query, $params, $columns);

        // 查询
        $this->indexQueryResult = $this->indexQuery($this->query, $params, $this->indexColumns);
        if ($this->indexQueryResult->isEmpty()) {
            $this->noData();
        }
    }

    /**
     * show 处理
     */
    protected function showHandler()
    {
        // 获取连表查询合格的列
        $columns = $this->query->getModel()->getQualifiedColumns(
            $this->getFillable($this->showColumns)
        );

        // 默认通过主键查询
        $this->showQueryResult = $this->query
            ->where($this->getQualifiedPrimaryKey(), $this->params->id)
            ->firstOr($columns, function () {
                $this->noData();
            });
    }

    /**
     * store 处理
     * @throws ResultException
     */
    protected function storeHandler()
    {
        // 获取保存数据
        $columns = $this->getFillable($this->storeColumns);
        $data = $this->filterSaveData(
            $this->getSaveData($columns),
            $this->storeDataFilter
        );
        if (empty($data)) {
            $this->error(trans('save_data_no_exist'));
        }

        // 保存
        $model = $this->query
            ->create($data);

        // 当前这条数据id
        $this->params->id = $model->{$model->getKeyName()};
    }

    /**
     * update 处理
     * @throws ResultException
     */
    protected function updateHandler()
    {
        // 字段数据
        $columns = $this->updateColumns;
        if (empty($columns)) {
            $columns = $this->storeColumns;
        }

        // 获取保存数据
        $columns = $this->getFillable($columns);
        $data = $this->filterSaveData(
            $this->getSaveData($columns),
            $this->updateDataFilter
        );

        // 无数据不更新
        if (empty($data)) {
            return;
        }

        // 不存在主键
        if (!$this->params->id) {
            $this->error(trans('update_model_no_exist'));
        }

        // 查询
        $primaryKey = $this->getQualifiedPrimaryKey();
        $info = $this->query
            ->where($primaryKey, $this->params->id)
            ->firstOr([$primaryKey], function () {
                $this->error(trans('update_data_query_failed'));
            });

        // 更新
        $info->fill($data)->save();
    }

    /**
     * destroy 处理
     * @throws ResultException
     */
    protected function destroyHandler()
    {
        // 删除id获取
        $ids = $this->destroyIdsObtain();

        // 删除
        $this->query
            ->whereIn($this->getQualifiedPrimaryKey(), $ids)
            ->get()
            ->each
            ->delete();
    }

    /**
     * index 查询
     * @param Builder $builder
     * @param array $params
     * @param array $columns
     * @return LengthAwarePaginator|Builder[]|Collection
     */
    protected function indexQuery(Builder $builder, array $params, array $columns)
    {
        // 获取连表查询合格的列
        $columns = $builder->getModel()->getQualifiedColumns($columns);

        // 最大数量
        $maxNum = config('lswl-api.max_query_num', 1000);
        // 修改参数
        $params = $this->modifyParams($params, $maxNum);

        // 是否分页
        if ($this->isPage()) {
            // 分页参数
            $page = !empty($params['page']) ? $params['page'] : DatabaseInterface::DEFAULT_PAGE;
            $pageSize = !empty($params['page_size']) ? $params['page_size'] : DatabaseInterface::DEFAULT_PAGE_SIZE;
            return $builder
                ->paginate($pageSize, $columns, 'page', $page);
        }

        // 启用分页
        if ($this->isEnablePage) {
            // 组装limit分页
            static::assembleLimit($builder, $params);
            // 组装page分页
            static::assemblePage($builder, $params);
        } else {
            // 未启用分页不超过最大限制
            $builder->offset(0)->limit($maxNum);
        }

        return $builder
            ->select($columns)
            ->get();
    }

    /**
     * index 集合处理
     * @param $list
     * @param Closure $closure
     * @return LengthAwarePaginator|mixed
     */
    protected function indexCollectionHandler($list, Closure $closure)
    {
        if ($list instanceof LengthAwarePaginator) {
            $list->setCollection($closure($list->getCollection()));
            return $list;
        }

        return $closure($list);
    }

    /**
     * index 成功返回
     * @param $list
     * @param array $fields
     * @return array|mixed
     */
    protected function indexSuccessResult($list, array $fields = [])
    {
        if (empty($fields)) {
            $fields = $this->resultPageFields;
        }

        return $this->isPage() ? PaginateHelper::paginate($list, $fields) : $list;
    }

    /**
     * 删除id获取
     * @return false|string[]
     * @throws ResultException
     */
    protected function destroyIdsObtain()
    {
        // 简单验证id
        $ids = explode(',', $this->params->id);
        if (empty($ids)) {
            $this->error(trans('destroy_data_no_exist'));
        }

        return $ids;
    }

    /**
     * 获取新的查询构造器
     * @return Builder
     */
    protected function getNewQuery(): Builder
    {
        // 模型
        $model = ParseModel::run($this->model, get_called_class(), 'service');
        if (!($model instanceof Model)) {
            $this->error(trans('model_no_exist'));
        }

        return $model->newQuery();
    }

    /**
     * 获取连表查询合格的主键
     */
    protected function getQualifiedPrimaryKey()
    {
        $model = $this->query->getModel();

        return $model->getQualifiedColumn($model->getKeyName());
    }

    /**
     * 初始化查询构造器
     */
    private function initQuery()
    {
        $this->query = $this->getNewQuery();
    }

    /**
     * 获取填充字段
     * @param array $columns
     * @return array
     */
    private function getFillable(array $columns): array
    {
        $res = $columns;

        if (in_array('*', $columns, true)) {
            // 使用模型的填充字段
            $res = $this->query->getModel()->getFillable();
        }

        if (empty($res) || in_array('*', $res, true)) {
            // 查询数据表字段
            $res = array_column(
                DBHelper::getInstance()->getAllColumns($this->query->getModel()->getTable()),
                'column_name'
            );
        }

        return $res;
    }

    /**
     * 修改参数
     * @param array $params
     * @param int $maxNum
     * @return array
     */
    private function modifyParams(array $params, int $maxNum): array
    {
        // 限制和页数都不存在
        if (empty($params['limit']) && empty($params['page_size'])) {
            $params['page'] = !empty($params['page']) ? $params['page'] : DatabaseInterface::DEFAULT_PAGE;
            $params['page_size'] = DatabaseInterface::DEFAULT_PAGE_SIZE;
        }

        // 限制过大
        if (!empty($params['limit']) && $params['limit'] > $maxNum) {
            $params['limit'] = $maxNum;
        }

        // 页数过大
        if (!empty($params['page_size']) && $params['page_size'] > $maxNum) {
            $params['page_size'] = $maxNum;
        }

        return $params;
    }

    /**
     * 操作保存数据
     * @param array $columns
     * @return array
     */
    private function getSaveData(array $columns): array
    {
        $res = [];
        foreach ($columns as $v) {
            // 字段名称
            if (!is_array($v)) {
                $res[$v] = $this->params->{$v};
                continue;
            }

            // 存在默认值的字段
            if (array_key_exists('column', $v) && array_key_exists('default', $v)) {
                $res[$v['column']] = $this->params->{$v['column']} ?: $v['default'];
                continue;
            }

            // 存在自定义的字段
            if (array_key_exists('column', $v) && array_key_exists('custom', $v)) {
                $res[$v['column']] = $v['custom'];
            }
        }

        return $res;
    }

    /**
     * 过滤数据
     * @param array $res
     * @param $filter
     * @return array
     */
    private function filterSaveData(array $res, $filter): array
    {
        // 过滤数据
        if (is_bool($filter) && $filter) {
            $filter = function ($v) {
                return isset($v);
            };
        }

        // 使用函数过滤
        if ($filter instanceof Closure) {
            $res = array_filter($res, $filter);
        }

        return $res;
    }
}
