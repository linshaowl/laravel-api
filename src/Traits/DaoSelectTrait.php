<?php

/**
 * (c) linshaowl <linshaowl@163.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lswl\Api\Traits;

use Illuminate\Support\Collection;

trait DaoSelectTrait
{
    /**
     * 查询包含指定了列值的数组
     * @param string $column
     * @param string $key
     * @return Collection
     */
    protected function selectPluck(string $column, string $key): Collection
    {
        return $this->model
            ->newQuery()
            ->pluck($column, $key);
    }

    /**
     * 查询主键指向列数据
     * @param string $column
     * @return Collection
     */
    protected function selectPkPointColumn(string $column): Collection
    {
        return $this->selectPluck($column, $this->model->getKeyName());
    }

    /**
     * 查询列指向列数据
     * @param string $column
     * @return Collection
     */
    protected function selectColumnPointColumn(string $column): Collection
    {
        return $this->selectPluck($column, $column);
    }
}
