<?php

/**
 * (c) linshaowl <linshaowl@163.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lswl\Api\Traits;

/**
 * 资源服务查询过滤
 */
trait ResourceServiceQueryFilterTrait
{
    /**
     * 查询字段
     * 格式: [操作符 => 字段数组, ...]
     * @see Builder::$operators 查看可用操作符
     * @var array
     */
    protected $queryColumns = [];

    /**
     * 查询字段-相等查询
     * @var array
     */
    protected $queryColumnsEqual = [];

    /**
     * 查询字段-模糊查询
     * @var array
     */
    protected $queryColumnsLike = [];

    /**
     * index 查询过滤
     */
    protected function indexQueryFilter()
    {
        // 查询操作符
        $this->queryOperator();
    }

    /**
     * 查询操作符
     */
    private function queryOperator()
    {
        // 合并查询字段
        $queryColumns = $this->mergeQueryColumns();

        // 添加查询条件
        foreach ($queryColumns as $operator => $columns) {
            foreach ($columns as $column) {
                // 参数不存在
                if (!isset($this->params->{$column})) {
                    continue;
                }

                // 参数为空字符串
                if ($this->params->{$column} === '') {
                    continue;
                }

                // 处理查询列值
                $value = $this->handlerQueryColumnValue($operator, $column);

                // 添加条件
                $this->query->where($this->query->getModel()->getQualifiedColumn($column), $operator, $value);
            }
        }
    }

    /**
     * 合并查询字段
     * @return array
     */
    private function mergeQueryColumns(): array
    {
        return array_merge_recursive(
            $this->queryColumns,
            [
                '=' => $this->queryColumnsEqual,
            ],
            [
                'like' => $this->queryColumnsLike,
            ]
        );
    }

    /**
     * 处理查询列值
     * @param string $operator
     * @param string $column
     * @return mixed
     */
    private function handlerQueryColumnValue(string $operator, string $column)
    {
        // 值
        $value = $this->params->{$column};

        // 模糊查询
        if (in_array($operator, ['like', 'not like', 'ilike', 'not ilike', 'like binary'], true) && is_string($value)) {
            return '%' . $value . '%';
        }

        return $value;
    }
}
