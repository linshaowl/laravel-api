<?php

/**
 * (c) linshaowl <linshaowl@163.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lswl\Api\Models;

use Illuminate\Database\Eloquent\Model;
use Lswl\Database\Contracts\DatabaseInterface;
use Lswl\Database\Scopes\PrimaryKeyDescScope;
use Lswl\Database\Traits\DatabaseTrait;

/**
 * 基础模型
 * @method DatabaseTrait initialize()
 */
class BaseModel extends Model implements DatabaseInterface
{
    use DatabaseTrait;

    /**
     * 是否使用主键倒序作用域
     * @var bool
     */
    protected static $usePrimaryKeyDescScope = true;

    protected function initializeBefore()
    {
        // 设置当前表名
        if (empty($this->table)) {
            $this->setTable(static::getSnakePluralName());
        }
    }

    protected static function boot()
    {
        parent::boot();

        if (static::$usePrimaryKeyDescScope) {
            static::addGlobalScope(PrimaryKeyDescScope::getInstance());
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getForeignKey()
    {
        return static::getSnakeSingularName() . '_' . $this->getKeyName();
    }
}
