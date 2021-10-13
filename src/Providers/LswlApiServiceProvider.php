<?php

/**
 * (c) linshaowl <linshaowl@163.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lswl\Api\Providers;

use Illuminate\Support\ServiceProvider;
use Lswl\Api\Middleware\CheckSdlMiddleware;
use Lswl\Api\Middleware\CheckSignatureMiddleware;
use Lswl\Api\Middleware\CheckTokenMiddleware;
use Lswl\Api\Middleware\CheckVersionMiddleware;
use Lswl\Api\Middleware\ConvertEmptyStringsToNullMiddleware;
use Lswl\Api\Middleware\ParamsHandlerMiddleware;
use Lswl\Api\Middleware\RequestLockMiddleware;
use Lswl\Api\Middleware\RequestLogMiddleware;
use Lswl\Api\Middleware\TrimStringsMiddleware;

/**
 * Api 服务提供者
 */
class LswlApiServiceProvider extends ServiceProvider
{
    /**
     * @var array
     */
    protected $routeMiddleware = [
        'lswl.params.handler' => ParamsHandlerMiddleware::class,
        'lswl.convert.empty.strings.to.null' => ConvertEmptyStringsToNullMiddleware::class,
        'lswl.trim.strings' => TrimStringsMiddleware::class,
        'lswl.request.lock' => RequestLockMiddleware::class,
        'lswl.request.log' => RequestLogMiddleware::class,
        'lswl.check.version' => CheckVersionMiddleware::class,
        'lswl.check.signature' => CheckSignatureMiddleware::class,
        'lswl.check.token' => CheckTokenMiddleware::class,
        'lswl.check.sdl' => CheckSdlMiddleware::class,
    ];

    /**
     * @var string
     */
    protected $apiConfigPath;

    /**
     * @var string
     */
    protected $apiDatabaseMigrationsDir;

    public function boot()
    {
        $this->apiConfigPath = __DIR__ . '/../../config/lswl-api.php';
        $this->apiDatabaseMigrationsDir = __DIR__ . '/../../database/migrations';

        // 注册路由中间件
        $this->registerRouteMiddleware();

        // 合并配置
        $this->mergeConfig();

        // 发布文件
        $this->publishFiles();
    }

    /**
     * 注册路由中间件
     */
    protected function registerRouteMiddleware()
    {
        // 注册路由
        foreach ($this->routeMiddleware as $key => $middleware) {
            app('router')->aliasMiddleware($key, $middleware);
        }
    }

    /**
     * 合并配置
     */
    protected function mergeConfig()
    {
        // 合并 api 配置
        $this->mergeConfigFrom(
            $this->apiConfigPath,
            'lswl-api'
        );
    }

    /**
     * 发布文件
     */
    protected function publishFiles()
    {
        // 发布配置文件
        $this->publishes(
            [
                $this->apiConfigPath => config_path('lswl-api.php'),
            ],
            'lswl-api-config'
        );

        // 发布迁移文件
        $this->publishes(
            [
                $this->apiDatabaseMigrationsDir => database_path('migrations'),
            ],
            'lswl-api-migrations'
        );

        // 发布所有文件
        $this->publishes(
            [
                $this->apiConfigPath => config_path('lswl-api.php'),
                $this->apiDatabaseMigrationsDir => database_path('migrations'),
            ],
            'lswl-api'
        );
    }
}
