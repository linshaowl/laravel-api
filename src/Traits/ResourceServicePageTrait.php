<?php

/**
 * (c) linshaowl <linshaowl@163.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lswl\Api\Traits;

use Lswl\Api\Utils\RequestParams;

/**
 * 资源服务分页方法
 */
trait ResourceServicePageTrait
{
    /**
     * 是否启用分页
     * @var bool
     */
    private $isEnablePage = true;

    /**
     * 是否返回分页字段
     * @var bool
     */
    private $isResultPageField = false;

    /**
     * 返回页码相关字段
     * @var array
     */
    private $resultPageFields = ['current_page', 'data', 'page_size', 'total'];

    /**
     * 是否分页
     * @return bool
     */
    private function isPage(): bool
    {
        // 是否启用分页并返回分页字段
        return $this->isEnablePage && $this->isResultPageField;
    }

    /**
     * 处理是否启用分页
     */
    private function handleIsEnablePage()
    {
        $this->handleBoolParam('isEnablePage', 'is_enable_page');
    }

    /**
     * 处理是否返回分页字段
     */
    private function handlerIsResultPageField()
    {
        $this->handleBoolParam('isResultPageField', 'is_result_page_field');
    }

    /**
     * 处理布尔参数
     * @param string $prop
     * @param string $name
     */
    private function handleBoolParam(string $prop, string $name)
    {
        $param = RequestParams::getParam(request(), $name, null, true);
        if (is_null($param)) {
            return;
        }

        $this->{$prop} = !!$param;
    }
}
