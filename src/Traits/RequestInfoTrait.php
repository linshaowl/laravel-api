<?php

/**
 * (c) linshaowl <linshaowl@163.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lswl\Api\Traits;

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Lswl\Api\Contracts\AgentInterface;
use Lswl\Api\Contracts\RequestParamsInterface;
use Lswl\Api\Utils\Agent;
use Lswl\Support\Utils\Collection;

/**
 * 请求信息
 */
trait RequestInfoTrait
{
    /**
     * app实例
     * @var Application
     */
    protected $app;

    /**
     * 请求实例
     * @var Request
     */
    protected $request;

    /**
     * 请求参数
     * @var Collection
     */
    protected $params;

    /**
     * 请求ip
     * @var string
     */
    protected $ip;

    /**
     * 请求 Agent
     * @var Agent
     */
    protected $agent;

    /**
     * 设置请求信息
     */
    private function setRequestInfo()
    {
        $this->app = app('app');
        $this->request = request();
        $this->params = app()->get(RequestParamsInterface::class);
        $this->ip = $this->request->ip();
        $this->agent = app()->get(AgentInterface::class);
    }
}
