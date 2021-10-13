<?php

/**
 * (c) linshaowl <linshaowl@163.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lswl\Api\Providers;

use Illuminate\Support\ServiceProvider;
use Lswl\Api\Contracts\AgentInterface;
use Lswl\Api\Contracts\RequestParamsInterface;
use Lswl\Api\Contracts\UserModelInterface;
use Lswl\Api\Contracts\VersionModelInterface;
use Lswl\Api\Models\UserModel;
use Lswl\Api\Models\VersionModel;
use Lswl\Api\Utils\Agent;
use Lswl\Support\Utils\Collection;

/**
 * 契约服务提供者
 */
class LswlContractServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // 请求参数
        app()->instance(RequestParamsInterface::class, new Collection());
        // 请求 Agent
        app()->instance(
            AgentInterface::class,
            Agent::getInstance(
                [
                    'request' => request(),
                    'userAgent' => request()->header('user-agent', ''),
                ]
            )
        );

        // 用户模型
        app()->instance(UserModelInterface::class, new UserModel());
        // 版本模型
        app()->instance(VersionModelInterface::class, new VersionModel());
    }
}
